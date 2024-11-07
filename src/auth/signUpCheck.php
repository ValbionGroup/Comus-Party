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
    define("FORBIDDEN_CHARACTERS", "!@#$%^&*()_+\\-=[\\]{};\':\"\\\\|,.<>/?");

    // Variables
    $validity=true;

    // Vérifications
    if(strlen($pUsername) < MIN_USERNAME_LENGTH) { $validity=false; }
    elseif(preg_match("/[" . FORBIDDEN_CHARACTERS . "]/", $pUsername)) {
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
    define("SPECIAL_CHARACTERS", "/[!@#$%^&*()_+\\-=[\\]{};':\"\\\\|,.<>/?]/");

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
 * Checks if a user with the given username and email already exists.
 *
 * The function verifies if the username and email are already used by another user.
 *
 * @param string $pUsername The username to be checked.
 * @param string $pEmail The email to be checked.
 * @return bool Returns true if the username and email are already used, false otherwise.
 */
function verifyDataAlreadyExist($pUsername, $pEmail): bool {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) { die("Connexion echouée: " . $conn->connect_error); }

    $sqlRequest = "SELECT u.id
                    FROM " . DB_PREFIX . "user u
                    JOIN " . DB_PREFIX . "player p ON u.id = p.user_id
                    WHERE u.email = $pEmail AND p.username = $pUsername;";

    $result = $conn->query($sqlRequest);

    return $result->num_rows > 0;
}

// Reception des données
$data = json_decode(file_get_contents("php://input"), true);

// Vérifie si les données sont fournies
if (isset($data['username']) &&
    isset($data['email']) &&
    isset($data['password'])) {
    $username = $data['username'];
    $email = $data['email'];
    $password = $data['password'];

    // Vérifications
    $correctDataFormat = false;
    $dataDontAlreadyExist = false;

    // Vérifie si les données respectent les exigences
    if(checkUsernameRequirements($username) &&
        checkEmailRequirements($email) &&
        checkPasswordRequirements($password)) {
        $correctDataFormat = true;
    }

    // Vérifie si les données existent dans la base de données
    if (!verifyDataAlreadyExist($username, $email))
    { $dataDontAlreadyExist = true; }

    if ($dataDontAlreadyExist && $correctDataFormat) {
        // Succès
        echo json_encode(["success" => true, "message" => "Inscription reussie."]);
    } else {
        // Echec
        echo json_encode(["success" => false, "message" => "Inscription echouee."]);
    }
} else {
    // Pas de username, email ou mot de passe fournis
    echo json_encode(["success" => false, "message" => "Donnees manquantes."]);
}


