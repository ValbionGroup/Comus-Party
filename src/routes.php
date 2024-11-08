<?php

use models\Router;

$router = Router::getInstance();

$router->get('/', function () {
    require __DIR__.'/../src/pages/index.php';
    exit;
});

$router->get('/play/:slug', function ($slug) {
    echo 'Liste des jeux : ' . $slug;
    exit;
});

// Route pour afficher le formulaire de connexion
$router->get('/login', function () use ($loader, $twig) {
    $controller = new ControllerAuth($loader, $twig);
    $controller->showLoginPage();
    exit;
});

// Route pour traiter la soumission du formulaire de connexion
$router->post('/login', function () use ($loader, $twig) {
    $controller = new ControllerAuth($loader, $twig);
    $controller->authenticate($_POST['email'], $_POST['password']);
    exit;
});