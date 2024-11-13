<?php

require_once  '../../include.php';

// Autorisation de toutes les origines et utilisation de JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

/**
 * Checks if the username meets the specified requirements.
 *
 * The function verifies if the username:
 * - has a minimum defined length
 * - does not contain forbidden special characters
 *
 * @param string $pUsername The username to be validated.
 * @return bool Returns true if the username is valid, false otherwise.
 */
function checkUsernameRequirements($pUsername): bool {
    // Constantes
    define("MIN_USERNAME_LENGTH",3);
    define("FORBIDDEN_CHARACTERS", '@#$%^&*()+=[]{}|;:",\'<>?/\\ ');

    // Variables
    $validity=true;

    // Vérifications
    if(strlen($pUsername) < MIN_USERNAME_LENGTH) { $validity=false; }
    elseif(strpbrk(FORBIDDEN_CHARACTERS, $pUsername)) {
        $validity=false;
    } else { $validity=true; }

    return $validity;
}

/**
 * Checks if the email is valid.
 *
 * The function verifies if the email contains an "@" symbol.
 *
 * @param string $pEmail The email to be validated.
 * @return bool Returns true if the email is valid, false otherwise.
 */
function checkEmailRequirements($pEmail): bool {
    return strpos($pEmail, "@") !== false;
}

/**
 * Checks if the password meets the specified requirements.
 *
 * The function verifies if the password:
 * - has a minimum defined length
 * - contains at least one uppercase letter
 * - contains at least one lowercase letter
 * - contains at least one number
 * - contains at least one special character
 *
 * @param string $pPassword The password to be validated.
 * @return bool Returns true if the password is valid, false otherwise.
 */
function checkPasswordRequirements($pPassword): bool {
    // Constantes
    define("MIN_PASSWORD_LENGTH",8);
    define("UPPERCASE_LETTER", "/[A-Z]/");
    define("LOWERCASE_LETTER", "/[a-z]/");
    define("NUMBERS", "/\d/");
    define("SPECIAL_CHARACTERS", "/[\W]/");

    // Variables
    $validity=true;

    // Vérifications
    if(strlen($pPassword) < MIN_PASSWORD_LENGTH) { $validity=false; }
    elseif(!preg_match(UPPERCASE_LETTER, $pPassword)) { $validity=false; }
    elseif(!preg_match(LOWERCASE_LETTER, $pPassword)) { $validity=false; }
    elseif(!preg_match(NUMBERS, $pPassword)) { $validity=false; }
    elseif(!preg_match(SPECIAL_CHARACTERS, $pPassword)) { $validity=false; }
    else { $validity=true; }

    return $validity;
}


/**
 * Checks if the given username already exists in the database.
 *
 * The function checks if a player with the given username exists in the database.
 *
 * @param string $pUsername The username to be validated.
 * @return bool Returns true if the username already exists, false otherwise.
 */
function checkUsernameExist($pUsername): bool {
    $conn = new mysqli(DB_HOST_LOCAL, DB_USER_LOCAL, DB_PASS_LOCAL, DB_NAME, 3307);
    if ($conn->connect_error) { die("Connexion echouée: " . $conn->connect_error); }

    $stmt = $conn->prepare("SELECT u.id
                                FROM " . DB_PREFIX . "user u
                                JOIN " . DB_PREFIX . "player p ON u.id = p.user_id
                                WHERE u.username = ?");
    $stmt->bind_param("s", $pUsername);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $conn->close();

    return $result->num_rows > 0;
}

/**
 * Checks if the given email already exists in the database.
 *
 * The function checks if a player with the given email exists in the database.
 *
 * @param string $pEmail The email to be validated.
 * @return bool Returns true if the email already exists, false otherwise.
 */
function checkEmailExist($pEmail): bool {
    $conn = new mysqli(DB_HOST_LOCAL, DB_USER_LOCAL, DB_PASS_LOCAL, DB_NAME, 3307);
    if ($conn->connect_error) { die("Connexion echouée: " . $conn->connect_error); }

    $stmt = $conn->prepare("SELECT u.id
                            FROM " . DB_PREFIX . "user u
                            JOIN " . DB_PREFIX . "player p ON u.id = p.user_id
                            WHERE u.email = ?");
    $stmt->bind_param("s", $pEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $conn->close();

    return $result->num_rows > 0;
}

// Reception des données
$data = json_decode(file_get_contents("php://input"), true);

// Vérifications
$correctDataFormat = false;
$usernameDontAlreadyExist = false;
$emailDontAlreadyExist = false;
$dataGiven = false;
$resultMessage="";

// Vérifie si les données sont fournies
if (isset($data['username']) &&
    isset($data['email']) &&
    isset($data['password'])) {
    $username = $data['username'];
    $email = $data['email'];
    $password = $data['password'];

    $dataGiven = true;

    // Vérifie si les données respectent les exigences
    if(checkUsernameRequirements($username) &&
        checkEmailRequirements($email) &&
        checkPasswordRequirements($password)) {
        $correctDataFormat = true;
    }

    // Vérifie si les données existent dans la base de données
    if(!checkUsernameExist($username))
    { $usernameDontAlreadyExist = true; }
    if(!checkEmailExist($email))
    { $emailDontAlreadyExist = true;}

    if(!$usernameDontAlreadyExist)
    { $resultMessage = "Le nom d'utilisateur existe déjà."; }
    if(!$emailDontAlreadyExist)
    { $resultMessage = "L'email existe déjà."; }
    if(!$correctDataFormat)
    { $resultMessage = "Format de données incorrecte."; }

    if(!$correctDataFormat ||
        !$emailDontAlreadyExist ||
        !$usernameDontAlreadyExist)
        {
            echo json_encode(["success" => false,"message" => $resultMessage]);
        }

    echo json_encode(["success" => false,"message" => $resultMessage]);

} else {
    // Pas de username, email ou mot de passe fournis
    $resultMessage = "Données manquantes.";
    echo json_encode(["success" => false,"message" => $resultMessage]);
}




