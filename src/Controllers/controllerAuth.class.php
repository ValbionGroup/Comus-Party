<?php
/**
 * @file    controllerAuth.class.php
 * @author  Estéban DESESSARD, Lucas ESPIET
 * @brief   Le fichier contient la déclaration & définition de la classe ControllerAuth.
 * @date    09/12/2024
 * @version 0.6
 */

namespace ComusParty\Controllers;

use ComusParty\App\Exception\AuthenticationException;
use ComusParty\App\Exception\MalformedRequestException;
use ComusParty\App\MessageHandler;
use ComusParty\App\Validator;
use ComusParty\Models\ArticleDAO;
use ComusParty\Models\ModeratorDao;
use ComusParty\Models\PasswordResetToken;
use ComusParty\Models\PasswordResetTokenDAO;
use ComusParty\Models\PlayerDAO;
use ComusParty\Models\UserDAO;
use DateMalformedStringException;
use DateTime;
use Exception;
use PHPMailer\PHPMailer\Exception as MailException;
use PHPMailer\PHPMailer\PHPMailer;
use Random\RandomException;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;


/**
 * @brief Classe ControllerAuth
 * @details La classe ControllerAuth est un contrôleur permettant de gérer l'authentification des utilisateurs (connexion & inscription)
 */
class ControllerAuth extends Controller
{

    /**
     * @brief Constructeur de la classe ControllerAuth
     * @param FilesystemLoader $loader Le loader de Twig
     * @param Environment $twig L'environnement de Twig
     */
    public function __construct(FilesystemLoader $loader, Environment $twig)
    {
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
     * @brief Affiche la page de réinitialisation de mot de passe
     * @return void
     * @throws RuntimeError Exception levée dans le cas d'une erreur d'exécution
     * @throws SyntaxError Exception levée dans le cas d'une erreur de syntaxe
     * @throws LoaderError Exception levée dans le cas d'une erreur de chargement
     */
    public function showForgotPasswordPage(): void
    {
        global $twig;
        echo $twig->render('forgot-password.twig');
    }

    /**
     * @param string $email Adresse e-mail pré-remplie dans le formulaire d'inscription
     * @return void
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
     * @throws RandomException Exception levée dans le cas d'une erreur de génération de nombre aléatoire
     * @todo Utiliser une template de mail quand disponible
     * @brief Envoie un lien de réinitialisation de mot de passe à l'adresse e-mail fournie
     */
    public function sendResetPasswordLink(string $email): void
    {
        $validator = new Validator([
            'email' => [
                'required' => true,
                'type' => 'string',
                'format' => FILTER_VALIDATE_EMAIL
            ]
        ]);

        if (!$validator->validate(['email' => $email])) {
            MessageHandler::addExceptionParametersToSession(new MalformedRequestException("Adresse e-mail invalide"));
            header('Location: /forgot-password');
            return;
        }

        $userManager = new UserDAO($this->getPdo());
        $user = $userManager->findByEmail($email);

        if (is_null($user)) {
            MessageHandler::addMessageParametersToSession("Un lien de réinitialisation de mot de passe vous a été envoyé par e-mail");
            header('Location: /login');
        }

        $tokenManager = new PasswordResetTokenDAO($this->getPdo());
        $token = new PasswordResetToken($user->getId(), bin2hex(random_bytes(32)), new DateTime());
        $tokenManager->insert($token);

        $url = BASE_URL . "/reset-password/" . $token->getToken();
        $to = $user->getEmail();
        $subject = "Réinitialisation de votre mot de passe";
        // TODO: Utiliser une template mail pour les mails dès que possible
        $message = "Bonjour, veuillez cliquer sur le lien suivant pour réinitialiser votre mot de passe : $url";

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = MAIL_HOST;
            $mail->SMTPAuth = true;
            $mail->Port = MAIL_PORT;
            $mail->Username = MAIL_USER;
            $mail->Password = MAIL_PASS;
            $mail->SMTPSecure = MAIL_SECURITY;
            $mail->setFrom(MAIL_FROM);
            $mail->isHTML();
            $mail->Subject = $subject . MAIL_BASE;
            $mail->AltBody = $message;
            $mail->Body = $message;
            $mail->CharSet = "UTF-8";
            $mail->Encoding = 'base64';

            $mail->addAddress($to);
            $mail->send();
        } catch (MailException $e) {
            MessageHandler::addExceptionParametersToSession($e);
            header('Location: /forgot-password');
            return;
        }

        MessageHandler::addMessageParametersToSession("Un lien de réinitialisation de mot de passe vous a été envoyé par e-mail");
        header('Location: /login');
    }

    /**
     * @brief Affiche la page de réinitialisation de mot de passe
     * @param string $token Token de réinitialisation de mot de passe
     * @throws SyntaxError Exception levée dans le cas d'une erreur de syntaxe
     * @throws RuntimeError Exception levée dans le cas d'une erreur d'exécution
     * @throws LoaderError Exception levée dans le cas d'une erreur de chargement
     * @throws DateMalformedStringException Exception levée dans le cas d'une date incorrecte
     * @throws MalformedRequestException Exception levée dans le cas d'une requête malformée
     */
    public function showResetPasswordPage(string $token): void
    {
        global $twig;

        $tokenManager = new PasswordResetTokenDAO($this->getPdo());
        $token = $tokenManager->findByToken($token);

        if (is_null($token)) {
            throw new MalformedRequestException("Token invalide");
        }

        echo $twig->render('reset-password.twig');
    }

    /**
     * @brief Réinitialise le mot de passe de l'utilisateur
     * @details Vérifie que le mot de passe et sa confirmation sont identiques, puis les hashes avant de les stocker en base de données
     * @param string $token Token de réinitialisation de mot de passe
     * @param string $password Nouveau mot de passe
     * @param string $passwordConfirm Confirmation du nouveau mot de passe
     * @throws MalformedRequestException Exception levée dans le cas d'une requête malformée
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
     * @throws Exception Exception levée dans le cas d'une erreur quelconque
     */
    public function resetPassword(string $token, string $password, string $passwordConfirm)
    {
        $rules = [
            'password' => [
                'required' => true,
                'minLength' => 8,
                'maxLength' => 120,
                'format' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&^#])[A-Za-z\d@$!%*?&^#]{8,}$/'
            ],
            'passwordConfirm' => [
                'required' => true,
                'minLength' => 8,
                'maxLength' => 120,
                'format' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&^#])[A-Za-z\d@$!%*?&^#]{8,}$/'
            ]
        ];
        $validator = new Validator($rules);
        $validated = $validator->validate([
            'password' => $password,
            'passwordConfirm' => $passwordConfirm
        ]);

        if (!$validated) {
            throw new MalformedRequestException($validator->getErrors());
        }

        if ($password !== $passwordConfirm) {
            throw new MalformedRequestException("Les mots de passe ne correspondent pas");
        }

        $tokenManager = new PasswordResetTokenDAO($this->getPdo());
        $token = $tokenManager->findByToken($token);

        if (is_null($token)) {
            throw new MalformedRequestException("Token invalide");
        }

        $userManager = new UserDAO($this->getPdo());

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $user = $userManager->findById($token->getUserId());
        $user->setPassword($hashedPassword);

        if (!$userManager->update($user)) {
            throw new Exception("Erreur lors de la mise à jour du mot de passe", 500);
        }

        if (!$tokenManager->delete($token->getUserId())) {
            throw new Exception("Erreur lors de la suppression du token", 500);
        }

        MessageHandler::addMessageParametersToSession("Votre mot de passe a bien été réinitialisé");
        header('Location: /login');
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
     * @throws AuthenticationException Exception levée dans le cas d'une erreur d'authentification
     * @throws DateMalformedStringException Erreur avec la création du DateTime
     */
    public function authenticate(?string $email, ?string $password): void
    {
        $regles = [
            'email' => [
                'required' => true,
                'type' => 'string',
                'format' => FILTER_VALIDATE_EMAIL
            ],
            'password' => [
                'required' => true,
                'type' => 'string',
                'min-length' => 8
            ]
        ];

        $validator = new Validator($regles);
        if (!$validator->validate(['email' => $email, 'password' => $password])) {
            throw new AuthenticationException("Adresse e-mail ou mot de passe invalide");
        }
        $userManager = new UserDAO($this->getPdo());
        $user = $userManager->findByEmail($email);

        if (is_null($user)) {
            throw new AuthenticationException("Adresse e-mail ou mot de passe invalide");
        }

        if (is_null($user->getEmailVerifiedAt())) {
            throw new AuthenticationException("Merci de vérifier votre adresse e-mail");
        }

        if ($user->getDisabled()) {
            throw new AuthenticationException("Votre compte a été désactivé");
        }

        if (!password_verify($password, $user->getPassword())) {
            throw new AuthenticationException("Adresse e-mail ou mot de passe invalide");
        }
        $moderatorManager = new ModeratorDAO($this->getPdo());
        $moderator = $moderatorManager->findByUserId($user->getId());

        $playerManager = new PlayerDAO($this->getPdo());
        $player = $playerManager->findByUserId($user->getId());

        if (is_null($player) && is_null($moderator)) {
            throw new AuthenticationException("Aucun joueur ou modérateur n'est associé à votre compte. Veuillez contacter un administrateur.");
        } elseif (!is_null($player)) {
            $_SESSION['role'] = 'player';
            $_SESSION['uuid'] = $player->getUuid();
            $_SESSION['username'] = $player->getUsername();
            $_SESSION['comusCoin'] = $player->getComusCoin();
            $_SESSION['elo'] = $player->getElo();
            $_SESSION['xp'] = $player->getXp();
            $_SESSION['basket'] = [];

            $articleManager = new ArticleDAO($this->getPdo());
            $pfp = $articleManager->findActivePfpByPlayerUuid($player->getUuid());
            if (is_null($pfp)) {
                $pfpPath = 'default-pfp.jpg';
            } else {
                $pfpPath = $pfp->getFilePath();
            }
            $_SESSION['pfpPath'] = $pfpPath;
            header('Location: /');
        } else {
            $_SESSION['role'] = 'moderator';
            $_SESSION['uuid'] = $moderator->getUuid();
            $_SESSION['firstName'] = $moderator->getFirstName();
            $_SESSION['lastName'] = $moderator->getLastName();
            header('Location: /');
        }

    }

    /**
     * @brief Déconnecte l'utilisateur
     * @details Commence par démarrer la session afin de pouvoir y supprimer toutes les variables stockées dessus, puis détruit celle-ci.
     * @return void
     */
    public function logOut(): void
    {
        session_unset();
        session_destroy();
        header('Location: /login');
    }




    /**
     * @brief La méthode register permet d'inscrire un utilisateur
     * @details Vérifie si un utilisateur portant l'adresse e-mail fournie en paramètre existe.
     * Si oui, lève une exception.
     * Vérifie que le nom d'utilisateur fourni n'existe pas déjà en base de données.
     * Si oui, lève une exception.
     * Crée un utilisateur avec l'adresse e-mail fournie, un mot de passe hashé fourni
     * et un token de vérification de l'email.
     * Envoie un mail avec phpmailer contenant un lien de confirmation de compte.
     * Crée un joueur avec l'identifiant utilisateur créé, le nom d'utilisateur fourni et l'adresse e-mail fournie.
     * @param ?string $username Nom d'utilisateur fourni dans le formulaire d'inscription
     * @param ?string $email Adresse e-mail fournie dans le formulaire d'inscription
     * @param ?string $password Mot de passe fourni dans le formulaire d'inscription
     * @return void
     * @throws AuthenticationException Exception levée dans le cas d'une erreur d'authentification
     * @todo Modifier le corps du mail (version HTMl) pour correspondre à la charte graphique (quand terminée)
     */
    public function register(?string $username, ?string $email, ?string $password): void {

        $rules = [
            'username' => [
                'required' => true,
                'type' => 'string',
                'min-length' => 3
            ],
            'email' => [
                'required' => true,
                'type' => 'string',
                'format' => FILTER_VALIDATE_EMAIL
            ],
            'password' => [
                'required' => true,
                'type' => 'string',
                'min-length' => 8
            ]
        ];

        $validator = new Validator($rules);

        if(!$validator->validate(['username' => $username, 'email' => $email, 'password' => $password])) {
            throw new AuthenticationException("Nom d'utilisateur, adresse e-mail ou mot de passe invalide");
        }

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
                $mail->CharSet = 'UTF-8';
                $mail->Encoding = 'base64';

                // Configuration de l'authentification
                $mail->Username = MAIL_USER; // Nom d'utilisateur de l'expéditeur
                $mail->Password = MAIL_PASS; // Mot de passe de l'expéditeur
                $mail->setFrom(MAIL_FROM); // Adresse de l'expéditeur
                $mail->addAddress($email); // Adresse du destinataire

                // Configuration du message
                $mail->isHTML(true); // Utilisation du format HTML pour le corps du message
                $mail->Subject = 'Confirmation de votre compte' . MAIL_BASE; // Sujet du message
                $mail->Body = // Corps du message
                    '<p>Vous avez créé un compte sur Comus Party.</p>
                    <p>Pour confirmer votre compte, cliquez sur le lien ci-dessous.</p>
                    <a href="' . BASE_URL . '/confirm-email/' . urlencode($emailVerifToken) . '"><button>Confirmer mon compte</button></a>';
                $mail->AltBody = // Corps du message sans format HTML
                    'Vous avez créé un compte sur Comus Party.
                    Pour confirmer votre compte, cliquez sur le lien ci-dessous.
                    "' . BASE_URL . '/confirm-email/' . urlencode($emailVerifToken);

                $mail->send(); // Envoi du message
            } catch (Exception $e) { echo "Le mail n'a pas pu être envoyé. Erreur Mailer: {$mail->ErrorInfo}"; }
        }

        // Créer le joueur si l'utilisateur est créé avec succès
        if ($resultUser) { $playerDAO->createPlayer($username, $email); }

        $userManager = new UserDAO($this->getPdo());
        $user = $userManager->findByEmail($email);

        if (is_null($user)) {
            throw new AuthenticationException("Adresse e-mail ou mot de passe invalide");
        }

        $playerManager = new PlayerDAO($this->getPdo());
        $player = $playerManager->findWithDetailByUserId($user->getId());

        if (is_null($player)) {
            throw new AuthenticationException("Aucun joueur ou modérateur n'est associé à votre compte. Veuillez contacter un administrateur.");
        }

        MessageHandler::addMessageParametersToSession("Votre compte a été créé et un mail de confirmation vous a été envoyé. Veuillez confirmer votre compte pour pouvoir vous connecter.");
        header('Location: /login');
        exit;
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
            MessageHandler::addMessageParametersToSession("Votre compte a bien été confirmé. Vous pouvez maintenant vous connecter.");
            header('Location: /login');
            exit;
        } else {
            header('Location: /register');
            throw new AuthenticationException("La confirmation a echoué");
        }
    }
}