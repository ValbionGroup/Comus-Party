<?php

// Autorisation de toutes les origines et utilisation de JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

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
    // (données temporaires)
    if ($username === "abc" &&
        $email === "a@b.c" &&
        $password === "abcABC123=$*") {
        // Succès
        echo json_encode(["success" => true, "message" => "Inscription reussie."]);
    } else {
        // Echec
        echo json_encode(["success" => false, "message" => "Inscription echouée."]);
    }
} else {
    // Pas de username, email ou mot de passe fournis
    echo json_encode(["success" => false, "message" => "Données manquantes."]);
}


