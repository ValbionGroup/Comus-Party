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
