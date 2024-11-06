<?php

use models\RouteNotFoundException;
use models\Router;

require_once 'include.php';

$router = Router::getInstance();

$router->get('/', function () {
    echo 'Hello World';
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
}