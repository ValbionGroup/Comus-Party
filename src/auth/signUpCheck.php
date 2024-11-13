<?php

require_once  '../../include.php';
use Ramsey\Uuid\Uuid; // Pour la création de l'uuid

// Constantes
// Nom d'utilisateur
const MIN_USERNAME_LENGTH =3;
const FORBIDDEN_CHARACTERS = '@#$%^&*()+=[]{}|;:",\'<>?/\\ ';
// Mot de passe
const MIN_PASSWORD_LENGTH = 8;
const UPPERCASE_LETTER = "/[A-Z]/";
const LOWERCASE_LETTER = "/[a-z]/";
const NUMBERS = "/\d/";
const SPECIAL_CHARACTERS = "/[\W]/";

/* Autorisation de toutes les origines car nous utilisons des requêtes AJAX
* pour communiquer avec le serveur, or les navigateurs interdisent
* les requêtes AJAX vers un serveur qui n'est pas le même que le fichier
* HTML qui envoie la requête, sauf si le serveur distant autorise
* les requêtes en provenance de n'importe quelle origine
*/
header("Access-Control-Allow-Origin: *");
// Utilisation de JSON
header("Content-Type: application/json");

/**
 * @brief Vérifie si le nom d'utilisateur respecte les exigences.
 *
 * @details La fonction vérifie si le nom d'utilisateur:
 * - possède une longueur minimale de 3 caractères
 * - ne contient pas de caractères speciaux interdits
 *
 * @param string $pUsername Le nom d'utilisateur à verifier.
 * @return bool Retourne true si le nom d'utilisateur respecte les exigences, false sinon.
 */
function checkUsernameRequirements($pUsername): bool {
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
 * @brief Checks if the email is valid.
 *
 * @details The function verifies if the email contains an "@" symbol.
 *
 * @param string $pEmail The email to be validated.
 * @return bool Returns true if the email is valid, false otherwise.
 */
function checkEmailRequirements($pEmail): bool {
    return strpos($pEmail, "@") !== false;
}

/**
 * @brief Checks if the password meets the specified requirements.
 *
 * @details The function verifies if the password:
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
 * @brief Checks if the given username already exists in the database.
 *
 * @details The function checks if a player with the given username exists in the database.
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
                                WHERE p.username = ?");
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

// Reception des données du client
$data = json_decode(file_get_contents("php://input"), true);

// Variables de vérifications
$correctDataFormat = false;
$usernameDontAlreadyExist = false;
$emailDontAlreadyExist = false;
$dataGiven = false;
$resultMessage="Succès";

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

    // Modifie le message de retour en fonction des erreurs
    if(!$usernameDontAlreadyExist)
    { $resultMessage = "Le nom d'utilisateur existe déjà."; }
    if(!$emailDontAlreadyExist)
    { $resultMessage = "L'email existe déjà."; }
    if(!$correctDataFormat)
    { $resultMessage = "Format de données incorrect."; }

    // Si au moins une erreur, renvoie un message d'erreur et arrete le script
    if(!$correctDataFormat ||
        !$emailDontAlreadyExist ||
        !$usernameDontAlreadyExist)
        {
            echo json_encode(["success" => false,"message" => $resultMessage]);
            exit();
        }

    // Hashing du mot de passe
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Connexion à la base de données
    $conn = new mysqli(DB_HOST_LOCAL, DB_USER_LOCAL, DB_PASS_LOCAL, DB_NAME, 3307);
    if ($conn->connect_error) { die("Connexion echouée: " . $conn->connect_error); }
    
    // Création de l'utilisateur
    $stmtUser = $conn->prepare("INSERT INTO " . DB_PREFIX . "user (email, password, email_verified_at, email_verify_token, disabled) VALUES (?, ?, null, null, 0)");
    $stmtUser->bind_param("ss", $email, $hashedPassword);
    $stmtUser->execute();

    // Récupération de l'id de l'utilisateur creé
    $stmtUserId = $conn->prepare("SELECT id FROM " . DB_PREFIX . "user WHERE email = ?");
    $stmtUserId->bind_param("s", $email);
    $stmtUserId->execute();
    $result = $stmtUserId->get_result();
    $row = $result->fetch_assoc();

    // Genération de l'uuid du joueur
    $uuid = Uuid::uuid4()->toString();

    // Création du joueur
    $stmtPlayer = $conn->prepare("INSERT INTO " . DB_PREFIX . "player (uuid, username, user_id) VALUES (?, ?, ?)");
    $stmtPlayer->bind_param("sss", $uuid, $username, $row['id']);
    $stmtPlayer->execute();
    
    // Fermeture des stmt
    $stmtUser->close();
    $stmtUserId->close();
    $stmtPlayer->close();
    // Fermeture de la connexion
    $conn->close();

    // Arriver à ce point, toutes les données sont validées
    $resultMessage = "Inscription validée.";
    echo json_encode(["success" => true,"message" => $resultMessage]);
    exit();

} else {
    // Pas de username, email ou mot de passe fournis
    $resultMessage = "Données manquantes.";
    echo json_encode(["success" => false,"message" => $resultMessage]);
    exit();
}




