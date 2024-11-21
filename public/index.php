<?php

global $router;

use ComusParty\Models\Exception\ErrorHandler;
use ComusParty\Models\Exception\NotFoundException;

require __DIR__.'/../include.php';

require __DIR__.'/../src/routes.php';

try {
    $router->matchRoute();
} catch (NotFoundException $e) {
    ErrorHandler::displayFullScreenError($e);
    exit;
}