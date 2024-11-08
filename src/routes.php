<?php

use models\Router;

$router = Router::getInstance();

$router->get('/', function () {
    global $loader, $twig;
    ControllerFactory::getController("game",$loader,$twig)->call("show");
    exit;
});

$router->get('/play/:slug', function ($slug) {
    echo 'Liste des jeux : ' . $slug;
    exit;
});
