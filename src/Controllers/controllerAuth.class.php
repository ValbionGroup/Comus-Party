<?php
/**
 * @file    controllerAuth.class.php
 * @author  Estéban DESESSARD, Lucas ESPIET
 * @brief   Le fichier contient la déclaration & définition de la classe ControllerAuth.
 * @date    09/12/2024
 * @version 0.6
 */

namespace ComusParty\Controllers;

use ComusParty\Models\ArticleDAO;
use ComusParty\Models\Exception\AuthenticationException;
use ComusParty\Models\Exception\MalformedRequestException;
use ComusParty\Models\Exception\MessageHandler;
use ComusParty\Models\PasswordResetToken;
use ComusParty\Models\PasswordResetTokenDAO;
use ComusParty\Models\PlayerDAO;
use ComusParty\Models\UserDAO;
use ComusParty\Models\Validator;
use DateMalformedStringException;
use DateTime;
use Exception;
use PHPMailer\PHPMailer\Exception as MailException;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
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
            $mail->isHTML(true);
            $mail->Subject = $subject . MAIL_BASE;
            $mail->AltBody = $message;
            $mail->Body = $message;

            $mail->addAddress($to);
            $mail->send();
        } catch (MailException $e) {
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
            var_dump($validator->getErrors());
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

        $playerManager = new PlayerDAO($this->getPdo());
        $player = $playerManager->findWithDetailByUserId($user->getId());

        if (is_null($player)) {
            throw new AuthenticationException("Aucun joueur ou modérateur n'est associé à votre compte. Veuillez contacter un administrateur.");
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
    public function logOut(): void
    {
        session_start();
        session_unset();
        session_destroy();
        header('Location: /login');
    }
}