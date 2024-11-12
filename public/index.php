<?php

global $router;
use models\RouteNotFoundException;

require __DIR__.'/../include.php';

require __DIR__.'/../src/routes.php';

try {
    $router->matchRoute();
} catch (RouteNotFoundException $e) {
    displayError($e);
    exit;
}