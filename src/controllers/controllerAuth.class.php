<?php
/**
 * @file    controllerAuth.class.php
 * @author  Estéban DESESSARD
 * @brief   Le fichier contient la déclaration & définition de la classe ControllerFactory.
 * @date    14/11/2024
 * @version 0.4
 */

use models\AuthentificationException;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


/**
 * @brief Classe ControllerAuth
 * @details La classe ControllerAuth est un contrôleur permettant de gérer l'authentification des utilisateurs (connexion & inscription)
 */
class ControllerAuth extends Controller {

    /**
     * @brief Constructeur de la classe ControllerAuth
     * @param FilesystemLoader $loader Le loader de Twig
     * @param Environment $twig L'environnement de Twig
     */
    public function __construct(FilesystemLoader $loader, Environment $twig) {
        parent::__construct($loader, $twig);
    }

    /**
     * @brief La méthode showLoginPage permet d'afficher la page de connexion
     * @return void
     * @throws LoaderError Exception levée dans le cas d'une erreur de chargement
     * @throws RuntimeError Exception levée dans le cas d'une erreur d'exécution
     * @throws SyntaxError Exception levée dans le cas d'une erreur de syntaxe
     */
    public function showLoginPage(): void
    {
        global $twig;
        echo $twig->render('login.twig');
    }

    
    /**
     * @brief La méthode showRegistrationPage permet d'afficher la page d'inscription
     * @return void
     * @throws LoaderError Exception levée dans le cas d'une erreur de chargement
     * @throws RuntimeError Exception levée dans le cas d'une erreur d'exécution
     * @throws SyntaxError Exception levée dans le cas d'une erreur de syntaxe
     */
    public function showRegistrationPage(): void
    {
        global $twig;
        echo $twig->render('signUp.twig');
    }

    /**
     * @brief Traite la demande de connexion de l'utilisateur
     * @details Vérifie si un utilisateur portant l'adresse e-mail fournie en paramètre existe.
     * Si oui, vérifie par la suite son adresse e-mail a bien été vérifiée. Dans le cas contraire, lève une exception
     * Vérifie ensuite que le compte n'est pas désactivé. Dans le cas contraire, lève une exception.
     * Vérifie que le mot de passe fourni correspond bien au mot de passe inscrit en base de données.
     * Si toutes les vérifications passent, ouvre la session et renseigne les éléments importants en variables de session.
     * @param ?string $email Adresse e-mail fournie dans le formulaire de connexion
     * @param ?string $password Mot de passe fourni dans le formulaire de connexion
     * @return void
     * @throws AuthentificationException Exception levée dans le cas d'une erreur d'authentification
     * @throws DateMalformedStringException
     */
    public function authenticate(?string $email, ?string $password): void
    {
        $userManager = new UserDAO($this->getPdo());
        $user = $userManager->findByEmail($email);

        if (is_null($user)) {
            throw new AuthentificationException("Adresse e-mail ou mot de passe invalide");
        }

        if (is_null($user->getEmailVerifiedAt())) {
            throw new AuthentificationException("Merci de vérifier votre adresse e-mail");
        }

        if ($user->getDisabled()) {
            throw new AuthentificationException("Votre compte a été désactivé");
        }

        if (!password_verify($password, $user->getPassword())) {
            throw new AuthentificationException("Adresse e-mail ou mot de passe invalide");
        }

        $playerManager = new PlayerDAO($this->getPdo());
        $player = $playerManager->findWithDetailByUserId($user->getId());

        if (is_null($player)) {
            throw new AuthentificationException("Aucun joueur ou modérateur n'est associé à votre compte. Veuillez contacter un administrateur.");
        }

        $_SESSION['uuid'] = $player->getUuid();
        $_SESSION['username'] = $player->getUsername();
        $_SESSION['comusCoin'] = $player->getComusCoin();
        $_SESSION['elo'] = $player->getElo();
        $_SESSION['xp'] = $player->getXp();

        $articleManager = new ArticleDAO($this->getPdo());
        $pfp = $articleManager->findActivePfpByPlayerUuid($player->getUuid());
        if (is_null($pfp)) {
            $pfpPath = 'default-pfp.jpg';
        } else {
            $pfpPath = $pfp->getPathImg();
        }
        $_SESSION['pfpPath'] = $pfpPath;
        header('Location: /');
    }

    /**
     * @brief Déconnecte l'utilisateur
     * @details Commence par démarrer la session afin de pouvoir y supprimer toutes les variables stockées dessus, puis détruit celle-ci.
     * @return void
     */
    public function logOut() : void {
        session_start();
        session_unset();
        session_destroy();
        header('Location: /login');
    }

    

    /**
     * @brief Enregistre un nouvel utilisateur et son joueur associé et envoie un email de confirmation d'email
     * @details Vérifie si l'email, le nom d'utilisateur et le mot de passe sont valides, puis crée un nouvel utilisateur et son joueur associé
     * en base de données. Si l'utilisateur est créé, envoi un email de confirmation d'email.
     * Si l'utilisateur et le joueur existent déjà, la méthode renvoie un message d'erreur approprié.
     * @param ?string $username Le nom d'utilisateur du joueur
     * @param ?string $email L'adresse e-mail de l'utilisateur
     * @param ?string $password Le mot de passe de l'utilisateur
     * @return void
     * @todo Modifier le corps du mail (version HTMl) pour correspondre à la charte graphique (quand terminée)
     * @todo Changer l'URL envoyé (localhost) pour le déploiement
     */
    public function register(?string $username, ?string $email, ?string $password): void {
        // Vérifier si l'email, le nom d'utilisateur et le mot de passe sont valides
        if ($this->validateUsername($username) &&
            $this->validateEmail($email) &&
            $this->validatePassword($password)) {

            // Initialiser la variable de message de résultat
            $resultMessage = null;

            // Hash le mot de passe
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);


            $userDAO = new UserDAO($this->getPdo());
            $playerDAO = new PlayerDAO($this->getPdo());
            
            // Vérifier si l'utilisateur et le joueur existent
            $existingUser = $userDAO->findByEmail($email) !== null;
            $existingPlayer = $playerDAO->findByUsername($username) !== null;
            
            $resultUser = false;
            
            // Si l'utilisateur et le joueur n'existent pas, créer l'utilisateur
            if (!$existingUser && !$existingPlayer)
            {
                $emailVerifToken = bin2hex(random_bytes(30)); // Générer un token de vérification de l'email
                $resultUser = $userDAO->createUser($email, $hashedPassword, $emailVerifToken);

                // Envoi du mail avec phpmailer
                $mail = new PHPMailer(true); // Création d'un objet PHPMailer
                try {
                    // Configuration technique
                    $mail->isSMTP(); // Utilisation du protocole SMTP
                    $mail->Host = MAIL_HOST; // Hôte du serveur SMTP
                    $mail->SMTPAuth = true; // Authentification SMTP
                    $mail->SMTPSecure = MAIL_SECURITY; // Cryptage SMTP
                    $mail->Port = MAIL_PORT; // Port SMTP

                    // Configuration de l'authentification
                    $mail->Username = MAIL_USER; // Nom d'utilisateur de l'expéditeur
                    $mail->Password = MAIL_PASS; // Mot de passe de l'expéditeur
                    $mail->setFrom(MAIL_FROM); // Adresse de l'expéditeur
                    $mail->addAddress($email); // Adresse du destinataire

                    // Configuration du message
                    $mail->isHTML(true); // Utilisation du format HTML pour le corps du message
                    $mail->Subject = 'Confirmation de votre compte' . MAIL_BASE; // Sujet du message
                    $mail->Body = // Corps du message
                        '<p>Vous avez créer un compte sur Comus Party.</p>
                        <p>Pour confirmer votre compte, cliquez sur le lien ci-dessous.</p>
                        <a href="http://localhost:8000/confirm-email/' . urlencode($emailVerifToken) . '"><button>Confirmer mon compte</button></a>';
                    $mail->AltBody = // Corps du message sans format HTML
                        'Vous avez créer un compte sur Comus Party.
                        Pour confirmer votre compte, cliquez sur le lien ci-dessous.
                        http://localhost:8000/confirm-email/' . urlencode($emailVerifToken);

                    $mail->send(); // Envoi du message
                } catch (Exception $e) { echo "Le mail n'a pas pu être envoyé. Erreur Mailer: {$mail->ErrorInfo}"; }
            }
            
            // Créer le joueur si l'utilisateur est créé avec succès
            if ($resultUser) { $playerDAO->createPlayer($username, $email); }

            if (!$existingUser && !$existingPlayer) { $resultMessage = "Inscription validée"; }
            elseif(!$existingUser && $existingPlayer) { $resultMessage = "Création du joueur échouée (nom d'utilisateur existant)"; }
            elseif($existingUser && !$existingPlayer) { $resultMessage = "Création de l'utilisateur échouée (email existant)"; }
            else { $resultMessage = "Création de l'utilisateur et du joueur échouées (email et nom d'utilisateur existants)"; }
        } else { $resultMessage = "Données reçues non valides (email, nom d'utilisateur ou mot de passe non valides)"; }

        $_SESSION['resultMessage'] = $resultMessage;

        global $twig;
        echo $twig->render('signUp.twig', ['resultMessage' => $resultMessage]);
    }

    /**
     * @brief Valide le nom d'utilisateur par rapport à des critères définis.
     *
     * @details Le nom d'utilisateur doit répondre aux critères suivants:
     * - Avoir au moins 3 caractères
     * - Ne pas contenir de caractères spéciaux
     *
     * @param string $username Le nom d'utilisateur à valider.
     * @return bool Renvoie true si le nom d'utilisateur répond à tous les critères, false sinon.
     */
    private function validateUsername($username) {
        return strlen($username) >= 3 && !strpbrk($username, '@#$%^&*()+=[]{}|;:",\'<>?/\\ ');
    }

    /**
     * @brief Valide le mot de passe par rapport à des critères définis.
     *
     * @details Le mot de passe doit répondre aux critères suivants:
     * - Avoir au moins 8 caractères
     * - Contenir au moins une lettre majuscule
     * - Contenir au moins une lettre minuscule
     * - Contenir au moins un chiffre
     * - Contenir au moins un caractère spécial
     *
     * @param string $password Le mot de passe à valider.
     * @return bool Renvoie true si le mot de passe répond à tous les critères, false sinon.
     */
    private function validatePassword($password) {
        return strlen($password) >= 8 && preg_match('/[A-Z]/', $password) &&
               preg_match('/[a-z]/', $password) && preg_match('/\d/', $password) &&
               preg_match('/[\W]/', $password);
    }

/**
 * @brief Valide l'email par rapport à des critères définis.
 *
 * @details Vérifie que l'email est dans un format valide utilisant le filtre PHP FILTER_VALIDATE_EMAIL.
 *
 * @param string $email L'email à valider.
 * @return bool Renvoie true si l'email est dans un format valide, false sinon.
 */
    private function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

/**
 * @brief Confirme l'adresse e-mail d'un utilisateur à l'aide du token de vérification.
 * 
 * @details Cette méthode utilise le token de vérification d'e-mail pour rechercher 
 * l'utilisateur dans la base de données. Si l'utilisateur est trouvé, son compte est 
 * confirmé et un message de confirmation est affiché. Sinon, un message d'erreur 
 * est affiché. Le résultat de la confirmation est ensuite rendu à l'aide de Twig.
 * 
 * @param string $emailVerifToken Le token de vérification d'e-mail de l'utilisateur.
 */
    public function confirmEmail($emailVerifToken) {
        $userDAO = new UserDAO($this->getPdo());
        $user = $userDAO->findByEmailVerifyToken($emailVerifToken);
        if ($user) {
            $userDAO->confirmUser($emailVerifToken);
            $confirmationResult='Votre compte a bien été créé';
            $confirmationValid = true;
        } else {
            $confirmationResult='La confirmation a echoué';
            $confirmationValid = false;
        }

        global $twig;
        echo $twig->render('confirmEmail.twig', ['confirmationResult' => $confirmationResult, 'confirmationValid' => $confirmationValid]);
    }
}