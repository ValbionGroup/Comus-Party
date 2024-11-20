<?php
/**
 * @file    controllerVerifEmail.class.php
 * @author  Enzo HAMID
 * @brief   Le fichier contient la déclaration & définition de la classe ControllerVerifEmail.
 * @date    20/11/2024
 * @version 0.1
 */

use models\AuthentificationException;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;


/**
 * @brief Classe ControllerVerifEmail
 * @details La classe ControllerVerifEmail est un contrôleur permettant de gérer la verification de l'email d'un utilisateur à l'inscription
 */
class ControllerVerifEmail extends Controller {
    private $userDAO;

    public function __construct($pdo) {
        $this->userDAO = new UserDAO($pdo);
    }

    public function showVerificationPage(): void {
        global $twig;
        echo $twig->render('emailVerification.twig');
    }

    public function verifyEmail(string $token): void {
        if (empty($token)) {
            $this->renderResponse("No verification token provided.");
            return;
        }

        $user = $this->userDAO->findByEmailVerifyToken($token);

        if ($user) {
            if (is_null($user->getEmailVerifiedAt())) {
                $this->userDAO->verifyEmail($user->getId());
                $this->renderResponse("Your email has been verified. You can now log in.");
            } else {
                $this->renderResponse("This email has already been verified.");
            }
        } else {
            $this->renderResponse("Invalid or expired verification token.");
        }
    }

    private function renderResponse(string $message): void
    {
        global $twig;
        echo $twig->render('verificationResponse.twig', ['message' => $message]);
    }
}

// Initialize the controller and handle the request
if (isset($_GET['token'])) {
    $controller = new ControllerVerifEmail($pdo);
    $controller->verifyEmail($_GET['token']);
} else {
    echo "Invalid request.";
}
