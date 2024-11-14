<?php
/**
 * @brief Fichier de définition des routes
 *
 * @file routes.php
 * @author Lucas ESPIET "lespiet@iutbayonne.univ-pau.fr"
 * @version 1.0
 * @date 2024-11-12
 */

global $loader, $twig;

use models\Router;

$router = Router::getInstance();

$router->get('/', function () use ($loader, $twig) {
    ControllerFactory::getController("game",$loader,$twig)->call("show");
    exit;
});

// Route pour afficher le profil
$router->get('/profile/:method/:uuid', function (string $method, string $uuid) use ($loader, $twig) {
    ControllerFactory::getController("profile", $loader, $twig)->call($method, [
        "playerUuid" => $uuid
    ]);
    exit;
});

// Route pour afficher le formulaire de connexion
$router->get('/login', function () use ($loader, $twig) {
    ControllerFactory::getController("auth", $loader, $twig)->call("showLoginPage");
    exit;
});

// Route pour traiter la soumission du formulaire de connexion
$router->post('/login', function () use ($loader, $twig) {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        ControllerFactory::getController("auth", $loader, $twig)->call("authenticate", [
            "email" => $_POST['email'],
            "password" => $_POST['password']
        ]);
        exit;
    }
    throw new Exception("Merci de renseigner une adresse e-mail et un mot de passe valides");
});

$router->get('/logout', function () use ($loader, $twig) {
    ControllerFactory::getController("auth", $loader, $twig)->call("logout");
    exit;
});

$router->get('/game/:code', function ($code) {
    echo "Page de jeu en cours : " . $code . "<br/>";
    echo "A IMPLEMENTER";
    exit;
});

$router->get('/shop', function () {
    echo "Page de la boutique<br/>";
    echo "A IMPLEMENTER";
    exit;
});

$router->get('/shop/basket', function () {
    echo "Page du panier<br/>";
    echo "A IMPLEMENTER";
    exit;
});

$router->post('/shop/basket/add/:id', function ($id) {
    echo "Ajout d'un article au panier : " . $id . "<br/>";
    echo "A IMPLEMENTER";
    exit;
});

$router->delete('/shop/basket/remove/:id', function ($id) {
    echo "Suppression d'un article du panier : " . $id . "<br/>";
    echo "A IMPLEMENTER";
    exit;
});

$router->get('/shop/basket/checkout', function () {
    echo "Page de paiement<br/>";
    echo "A IMPLEMENTER";
    exit;
});

$router->post('/shop/basket/checkout', function () {
    echo "Traitement du paiement<br/>";
    echo "A IMPLEMENTER";
    exit;
});

$router->get('/register', function () use ($loader, $twig) {
    ControllerFactory::getController("auth", $loader, $twig)->call("showRegistrationPage");
    exit;
});

$router->post('/register', function () use ($loader, $twig) {
    ControllerFactory::getController("user", $loader, $twig)->call("register");
    exit;
});

$router->put('/profile', function () {
    echo "Mise à jour du profil<br/>";
    echo "A IMPLEMENTER";
    exit;
});