<?php

use models\RouteNotFoundException;
use models\Router;

require_once __DIR__.'/../include.php';

$router = Router::getInstance();

$router->get('/', function () {
    require __DIR__.'/../src/pages/index.php';
    exit;
});

$router->get('/play/:slug', function ($slug) {
    echo 'Liste des jeux : ' . $slug;
    exit;
});

try {
    $router->matchRoute();
} catch (RouteNotFoundException $e) {
    echo $e->getMessage();
    exit;
}