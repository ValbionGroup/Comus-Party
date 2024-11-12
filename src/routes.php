<?php

global $loader, $twig;

use models\Router;

$router = Router::getInstance();

$router->get('/', function () use ($loader, $twig) {
    ControllerFactory::getController("game",$loader,$twig)->call("show");
    exit;
});

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
    throw new \Relay\Exception("Merci de renseigner une adresse e-mail et un mot de passe valides");
});

$router->get('/logout', function () use ($loader, $twig) {
    ControllerFactory::getController("auth", $loader, $twig)->call("logout");
    exit;
});