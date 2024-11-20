<?php

global $router;

use Models\RouteNotFoundException;

require __DIR__.'/../include.php';

require __DIR__.'/../src/routes.php';

try {
    $router->matchRoute();
} catch (RouteNotFoundException $e) {
    displayFullScreenError($e);
    exit;
}