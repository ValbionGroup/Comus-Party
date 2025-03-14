<?php
/**
 * @file    ControllerAuth.class.php
 * @author  Estéban DESESSARD, Lucas ESPIET
 * @brief   Fichier de déclaration et définition de la classe ControllerAuth
 * @date    09/12/2024
 * @version 0.6
 */

namespace ComusParty\Controllers;

use ComusParty\App\Cookie;
use ComusParty\App\Exceptions\AuthenticationException;
use ComusParty\App\Exceptions\MalformedRequestException;
use ComusParty\App\Mailer;
use ComusParty\App\MessageHandler;
use ComusParty\App\Validator;
use ComusParty\Models\ArticleDAO;
use ComusParty\Models\ModeratorDao;
use ComusParty\Models\PasswordResetToken;
use ComusParty\Models\PasswordResetTokenDAO;
use ComusParty\Models\PlayerDAO;
use ComusParty\Models\RememberToken;
use ComusParty\Models\RememberTokenDAO;
use ComusParty\Models\User;
use ComusParty\Models\UserDAO;
use DateMalformedStringException;
use DateTime;
use Exception;
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
        echo $twig->render('login.twig', [
            'turnstile_siteKey' => CF_TURNSTILE_SITEKEY
        ]);
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
            exit;
        }

        $tokenManager = new PasswordResetTokenDAO($this->getPdo());

        if ($tokenManager->findByUserId($user->getId())) {
            MessageHandler::addMessageParametersToSession("Un lien de réinitialisation de mot de passe vous a été envoyé par e-mail");
            header('Location: /login');
            exit;
        }

        $token = new PasswordResetToken($user->getId(), bin2hex(random_bytes(30)), new DateTime());
        $tokenManager->insert($token);

        $url = BASE_URL . "/reset-password/" . $token->getToken();
        $to = $user->getEmail();

        $subject = '🔑 Réinitialiser votre mot de passe';
        $message =
            '<p>Bonjour,</p>
                <p>Il semblerait que vous ayez fait une demande de réinitialisation de mot de passe.</p>
                <p>Pour ce faire, cliquez sur le lien ci-dessous et renseignez votre nouveau mot de passe :</p>
                <a href="' . $url . '">✅ Changer le mot de passe</a>
                <p>À très bientôt dans l’arène ! 🎲,<br>
                L\'équipe Comus Party 🚀</p>
                <br/><br/>
                <p style="font-size: 9px;">Si vous n\'êtes pas à l\'origine de cette demande, vous pouvez ignorer le présent mail.</p>';

        try {
            $mailer = new Mailer([$to], $subject, $message);
            $mailer->generateHTMLMessage();

            $mailer->send();
        } catch (Exception $e) {
            MessageHandler::addExceptionParametersToSession($e);
            header('Location: /forgot-password');
            return;
        }

        MessageHandler::addMessageParametersToSession("Un lien de réinitialisation de mot de passe vous a été envoyé par e-mail");
        header('Location: /login');
        exit;
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
                'format' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?_&^#])[A-Za-z\d@$!%*?_&^#]{8,}$/'
            ],
            'passwordConfirm' => [
                'required' => true,
                'minLength' => 8,
                'maxLength' => 120,
                'format' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?_&^#])[A-Za-z\d@$!%*?_&^#]{8,}$/'
            ]
        ];
        $validator = new Validator($rules);
        $validated = $validator->validate([
            'password' => $password,
            'passwordConfirm' => $passwordConfirm
        ]);

        if (!$validated) {
            throw new MalformedRequestException("Les  mot de passe ne respectent pas les règles de validation de mot de passe.");
        }

        if ($password !== $passwordConfirm) {
            throw new MalformedRequestException("Les mots de passe ne correspondent pas.");
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

        echo MessageHandler::sendJsonMessage("Votre mot de passe a bien été réinitialisé");
        exit;
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
        echo $twig->render('sign-up.twig', [
            "turnstile_siteKey" => CF_TURNSTILE_SITEKEY
        ]);
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
     * @param ?bool $rememberMe Booléen permettant de savoir si l'utilisateur souhaite rester connecté
     * @param ?string $cloudflareCaptchaToken Token de captcha fourni par Cloudflare
     * @return bool
     */
    public function login(?string $email, ?string $password, ?bool $rememberMe, ?string $cloudflareCaptchaToken): bool
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

        try {
            if (!$this->verifyCaptcha($cloudflareCaptchaToken)) {
                throw new AuthenticationException("Impossible de vérifier le captcha");
            }

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
                throw new AuthenticationException("Votre compte est désactivé");
            }

            if (!password_verify($password, $user->getPassword())) {
                throw new AuthenticationException("Adresse e-mail ou mot de passe invalide");
            }

            if ($this->authenticate($user)) {
                if ($rememberMe) {
                    $token = new RememberToken($user->getId());
                    $token->generateToken();
                    $key = $token->generateKey();

                    Cookie::set('rmb_token', base64_encode($token->getToken()), $token->getExpiresAt()->getTimestamp());
                    Cookie::set('rmb_usr', base64_encode($user->getId()), $token->getExpiresAt()->getTimestamp());
                    Cookie::set('rmb_key', base64_encode($key), $token->getExpiresAt()->getTimestamp());

                    (new RememberTokenDAO($this->getPdo()))->insert($token);
                }

                echo MessageHandler::sendJsonMessage("Vous êtes connecté en tant que " . ($_SESSION['role'] === 'player' ? "joueur" : "modérateur"));
                return true;
            } else {
                throw new AuthenticationException("Erreur lors de l'authentification");
            }
        } catch (Exception $e) {
            MessageHandler::sendJsonException($e);
            return false;
        }
    }

    /**
     * @brief Permet de vérifier le token de captcha auprès de Cloudflare
     * @param string $cloudflareCaptchaToken Token de captcha fourni par Cloudflare
     * @return bool Renvoie true si le captcha est valide, false sinon
     * @throws Exception Exception levée en cas d'erreur lors de la vérification du captcha
     */
    private function verifyCaptcha(string $cloudflareCaptchaToken): bool
    {
        $url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
        $data = [
            'secret' => CF_TURNSTILE_SECRETKEY,
            'response' => $cloudflareCaptchaToken,
            'remoteip' => $_SERVER['REMOTE_ADDR']
        ];

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_OPTIONS, CURLSSLOPT_NATIVE_CA);

        $result = curl_exec($curl);

        if (curl_errno($curl)) {
            curl_close($curl);
            throw new Exception("Erreur lors de la vérification du captcha : " . curl_error($curl));
        } else {
            $response = json_decode($result);
            curl_close($curl);
            return $response->success;
        }
    }

    /**
     * @brief Permet d'authentifier l'utilisateur
     * @details Vérifie si l'utilisateur est un joueur ou un modérateur, puis stocke les informations nécessaires en variables de session
     * @param User $user Utilisateur à authentifier
     * @return bool Renvoie true si l'utilisateur est authentifié, false sinon
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
     * @throws AuthenticationException Exception levée dans le cas d'une erreur d'authentification
     */
    private function authenticate(User $user): bool
    {
        $moderatorManager = new ModeratorDAO($this->getPdo());
        $moderator = $moderatorManager->findByUserId($user->getId());

        $playerManager = new PlayerDAO($this->getPdo());
        $player = $playerManager->findByUserId($user->getId());

        if (is_null($player) && is_null($moderator)) {
            throw new AuthenticationException("Aucun joueur ou modérateur n'est associé à votre compte. Veuillez contacter un administrateur.");
        }

        if (!is_null($player)) {
            $articleManager = new ArticleDAO($this->getPdo());
            $activePfp = $articleManager->findActivePfpByPlayerUuid($player->getUuid());
            $player->setActivePfp($activePfp == null ? "default-pfp.jpg" : $activePfp->getFilePath());
            $_SESSION['role'] = 'player';
            $_SESSION['uuid'] = $player->getUuid();
            $_SESSION['username'] = $player->getUsername();
            $_SESSION['comusCoin'] = $player->getComusCoin();
            $_SESSION['elo'] = $player->getElo();
            $_SESSION['xp'] = $player->getXp();
            $_SESSION['basket'] = [];
            $_SESSION['pfpPath'] = $player->getActivePfp();
        } else {
            $_SESSION['role'] = 'moderator';
            $_SESSION['uuid'] = $moderator->getUuid();
            $_SESSION['firstName'] = $moderator->getFirstName();
            $_SESSION['lastName'] = $moderator->getLastName();
        }
        return true;
    }

    /**
     * @brief Permet de reprendre la session via un cookie
     * @details Vérifie si les cookies de connexion sont présents, puis authentifie l'utilisateur si c'est le cas
     * @return bool Renvoie true si l'utilisateur est authentifié, false sinon
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
     * @throws AuthenticationException Exception levée dans le cas d'une erreur d'authentification
     */
    public function restoreSession(): bool
    {
        $token = Cookie::get('rmb_token');
        $userId = Cookie::get('rmb_usr');
        $key = Cookie::get('rmb_key');

        if (!$token && !$userId && !$key) {
            return false;
        }

        $tokenManager = new RememberTokenDAO($this->getPdo());
        $rmbToken = $tokenManager->find(intval(base64_decode($userId)), base64_decode($token));

        if (!$rmbToken) {
            $this->clearRememberCookies();
            return false;
        }

        if (!$rmbToken->isValid(base64_decode($key))) {
            $this->clearRememberCookies();
            return false;
        }

        if ($rmbToken->isExpired()) {
            $tokenManager->delete($rmbToken);
            $this->clearRememberCookies();
            return false;
        }

        $user = (new UserDAO($this->getPdo()))->findById(intval(base64_decode($userId)));

        if (!$user) {
            $this->clearRememberCookies();
            return false;
        }

        $ok = $this->authenticate($user);
        if ($ok) {
            // TODO: Trouver comment déplacer cette fonctionnalité pour qu'il n'y ai pas de code dupliqué...
            $this->getTwig()->addGlobal('auth', [
                'pfpPath' => $_SESSION['pfpPath'] ?? null,
                'loggedIn' => isset($_SESSION['uuid']),
                'loggedUuid' => $_SESSION['uuid'] ?? null,
                'loggedUsername' => $_SESSION['username'] ?? null,
                'loggedComusCoin' => $_SESSION['comusCoin'] ?? null,
                'loggedElo' => $_SESSION['elo'] ?? null,
                'loggedXp' => $_SESSION['xp'] ?? null,
                'role' => $_SESSION['role'] ?? null,
                'firstName' => $_SESSION['firstName'] ?? null,
                'lastName' => $_SESSION['lastName'] ?? null,
            ]);
        }
        return $ok;
    }

    /**
     * @brief Supprime les cookies de connexion
     * @return void
     */
    private function clearRememberCookies(): void
    {
        Cookie::delete('rmb_token');
        Cookie::delete('rmb_usr');
        Cookie::delete('rmb_key');
    }

    /**
     * @brief Déconnecte l'utilisateur
     * @details Commence par démarrer la session afin de pouvoir y supprimer toutes les variables stockées dessus, puis détruit celle-ci.
     * @return void
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
     */
    public function logOut(): void
    {
        session_unset();
        session_destroy();

        // Supprimer les cookies de connexion si existants
        $token = Cookie::get('rmb_token');
        $userId = Cookie::get('rmb_usr');

        if ($token && $userId) {
            $tokenManager = new RememberTokenDAO($this->getPdo());
            $rmbToken = $tokenManager->find(intval(base64_decode($userId)), base64_decode($token));
            if ($rmbToken) {
                $tokenManager->delete($rmbToken);
            }
        }

        $this->clearRememberCookies();

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
     * @param ?string $passwordConfirm Confirmation du mot de passe fourni dans le formulaire d'inscription
     * @param ?bool $termsOfServiceIsChecked Condition d'acceptation des conditions d'utilisation
     * @param ?bool $privacyPolicyIsChecked Condition d'acceptation de la politique de confidentialité
     * @param ?string $cloudflareCaptchaToken Token du captcha fourni par Cloudflare
     * @return void
     */
    public function register(?string $username, ?string $email, ?string $password, ?string $passwordConfirm, ?bool $termsOfServiceIsChecked, ?bool $privacyPolicyIsChecked, ?string $cloudflareCaptchaToken): void
    {
        $rules = [
            'username' => [
                'required' => true,
                'type' => 'string',
                'min-length' => 3,
                'max-length' => 25,
                'format' => '/^[a-zA-Z0-9_-]+$/'
            ],
            'email' => [
                'required' => true,
                'type' => 'string',
                'format' => FILTER_VALIDATE_EMAIL
            ],
            'password' => [
                'required' => true,
                'type' => 'string',
                'min-length' => 8,
                'max-length' => 120,
                'format' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?_&^#])[A-Za-z\d@$!%*?_&^#]{8,}$/'
            ],
            'passwordConfirm' => [
                'required' => true,
                'type' => 'string',
                'min-length' => 8,
                'max-length' => 120,
                'format' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?_&^#])[A-Za-z\d@$!%*?_&^#]{8,}$/'
            ]
        ];

        $validator = new Validator($rules);

        try {
            if (!$this->verifyCaptcha($cloudflareCaptchaToken)) {
                throw new AuthenticationException("Impossible de vérifier le captcha");
            }

            if (!$validator->validate(['username' => $username, 'email' => $email, 'password' => $password, 'passwordConfirm' => $passwordConfirm])) {
                throw new AuthenticationException("Nom d'utilisateur, adresse e-mail ou mot de passe invalide");
            }

            if ($password !== $passwordConfirm) {
                throw new AuthenticationException("Les mots de passe ne correspondent pas");
            }

            if (!$termsOfServiceIsChecked || !$privacyPolicyIsChecked) {
                throw new AuthenticationException("Vous devez accepter les conditions d'utilisation et la politique de confidentialité pour vous inscrire");
            }

            // Hash le mot de passe
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $userDAO = new UserDAO($this->getPdo());
            $playerDAO = new PlayerDAO($this->getPdo());

            // Vérifier si l'utilisateur et le joueur existent
            $existingUser = $userDAO->findByEmail($email) !== null;
            $existingPlayer = $playerDAO->findByUsername($username) !== null;

            if ($existingUser) {
                throw new AuthenticationException("L'adresse e-mail est déjà utilisée");
            }

            if ($existingPlayer) {
                throw new AuthenticationException("Le nom d'utilisateur est déjà utilisé");
            }

            // Si l'utilisateur et le joueur n'existent pas, créer l'utilisateur
            $emailVerifToken = bin2hex(random_bytes(30)); // Générer un token de vérification de l'email
            $resultUser = $userDAO->createUser($email, $hashedPassword, $emailVerifToken);

            if (!$resultUser) {
                throw new AuthenticationException("Erreur lors de la création de l'utilisateur");
            }

            $subject = '🎉 Bienvenue sur Comus Party !';
            $message =
                '<p>Merci d\'avoir créé un compte sur notre plateforme de mini-jeux en ligne. 🎮</p>
                    <p>Pour commencer à jouer et rejoindre nos parties endiablées, il ne vous reste plus qu\'une étape :</p>
                    <a href="' . BASE_URL . '/confirm-email/' . urlencode($emailVerifToken) . '">✅ Confirmer votre compte ici</a>
                    <p>À très bientôt dans l’arène ! 🎲,<br>
                    L\'équipe Comus Party 🚀</p>';

            $confirmMail = new Mailer(array($email), $subject, $message);
            $confirmMail->generateHTMLMessage();
            $confirmMail->send();

            // Créer le joueur si l'utilisateur est créé avec succès
            $playerDAO->createPlayer($username, $email);

            $userManager = new UserDAO($this->getPdo());
            $user = $userManager->findByEmail($email);

            if (is_null($user)) {
                throw new AuthenticationException("Erreur lors de la création de l'utilisateur");
            }

            $playerManager = new PlayerDAO($this->getPdo());
            $player = $playerManager->findWithDetailByUserId($user->getId());

            if (is_null($player)) {
                throw new AuthenticationException("Erreur lors de la création du joueur");
            }

            echo MessageHandler::sendJsonMessage("Votre compte a été créé et un mail de confirmation vous a été envoyé. Veuillez confirmer votre compte pour pouvoir vous connecter.");
            exit;

        } catch (Exception $e) {
            MessageHandler::sendJsonException($e);
        }
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
     * @throws AuthenticationException Exception levée dans le cas d'une erreur d'authentification
     */
    public function confirmEmail(string $emailVerifToken, bool $isLoggedIn): void
    {
        $userDAO = new UserDAO($this->getPdo());
        $user = $userDAO->findByEmailVerifyToken($emailVerifToken);
        if ($user) {
            $userDAO->confirmUser($emailVerifToken);
            MessageHandler::addMessageParametersToSession("Votre compte a bien été confirmé. Vous pouvez maintenant vous connecter.");

            if ($isLoggedIn) {
                header('Location: /');
            } else {
                header('Location: /login');
            }
            exit;
        } else {
            header('Location: /register');
            throw new AuthenticationException("La confirmation a echoué");
        }
    }

}