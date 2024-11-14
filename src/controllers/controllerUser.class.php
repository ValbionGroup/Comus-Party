<?php
/**
 * @file    controllerUser.class.php
 * @author  Enzo HAMID
 * @brief   Le fichier contient la déclaration & définition de la classe controllerUser.
 * @date    14/11/2024
 * @version 0.1
 */

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require_once 'C:/wamp64/www/Comus-Party/src/models/user.class.php';
require_once 'C:/wamp64/www/Comus-Party/src/models/user.dao.php';
require_once 'C:/wamp64/www/Comus-Party/src/models/player.class.php';
require_once 'C:/wamp64/www/Comus-Party/src/models/player.dao.php';


/**
 * La classe ControllerUser est un contrôleur permettant de gérer les utilisateurs
 */
class ControllerUser extends Controller {
    /**
     * Constructeur de la classe ControllerUser
     * 
     *
     * @param FilesystemLoader $loader
     * @param Environment $twig
     */
    public function __construct(FilesystemLoader $loader, Environment $twig) {
        parent::__construct($loader, $twig);
    }

    /**
     * @brief Registers a new user with the provided data.
     *
     * @details Validates the input data for email, username, and password format.
     * Checks if the email or username already exists in the database.
     * If validation passes and the user doesn't already exist, creates a new user
     * and associated player entry in the database.
     *
     * @param array $data Associative array containing 'email', 'username', and 'password'.
     * @return string JSON encoded response indicating success or failure of registration.
     */
    public function register(): void {
        $data = json_decode(file_get_contents("php://input"), true);

        // Validate input data (ensure username, email, and password are provided)
        if (isset($data['username']) && isset($data['email']) && isset($data['password'])) {
            $username = $data['username'];
            $email = $data['email'];
            $password = $data['password'];

            // Perform validation checks
            if ($this->validateUsername($username) &&
                $this->validateEmail($email) &&
                $this->validatePassword($password)) {

                // Hash the password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Save user to the database (using UserDAO or similar)
                $userDAO = new UserDAO($this->getPdo());
                $resultUser = $userDAO->createUser($email, $hashedPassword);

                // Save player to the database
                $playerDAO = new PlayerDAO($this->getPdo());
                $resultPlayer = $playerDAO->createPlayer($username, $email);

                if ($resultUser && $resultPlayer) { echo json_encode(["success" => true, "message" => "Registration successful"]);
                } else { echo json_encode(["success" => false, "message" => "Failed to register user"]); }
            } else { echo json_encode(["success" => false, "message" => "Invalid input data"]); }
        } else { echo json_encode(["success" => false, "message" => "Missing required fields"]); }
    }

    /**
     * @brief Validates a username against predefined security criteria.
     *
     * @details The username must meet the following requirements:
     * - Be at least 3 characters long
     * - Not contain any of the following special characters: @#$%^&*()+=[]{}|;:",\'<>?/\\
     *
     * @param string $username The username to validate.
     * @return bool Returns true if the username is valid, false otherwise.
     */
    private function validateUsername($username) {
        return strlen($username) >= 3 && !strpbrk($username, '@#$%^&*()+=[]{}|;:",\'<>?/\\ ');
    }

    /**
     * @brief Validates the password against predefined security criteria.
     *
     * @details The password must meet the following requirements:
     * - Be at least 8 characters long
     * - Contain at least one uppercase letter
     * - Contain at least one lowercase letter
     * - Contain at least one digit
     * - Contain at least one special character
     *
     * @param string $password The password to be validated.
     * @return bool Returns true if the password meets all the requirements, false otherwise.
     */
    private function validatePassword($password) {
        return strlen($password) >= 8 && preg_match('/[A-Z]/', $password) &&
               preg_match('/[a-z]/', $password) && preg_match('/\d/', $password) &&
               preg_match('/[\W]/', $password);
    }

    /**
     * @brief Validates the email format.
     *
     * @details The email must be a valid email address format as per FILTER_VALIDATE_EMAIL.
     *
     * @param string $email The email address to validate.
     * @return bool Returns true if the email format is valid, false otherwise.
     */
    private function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}
