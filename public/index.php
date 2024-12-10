<?php

global $router;

use ComusParty\Models\Exception\ErrorHandler;
use ComusParty\Models\Exception\NotFoundException;
use ComusParty\Models\Exception\UnauthorizedAccessException;

require __DIR__.'/../include.php';

require __DIR__.'/../src/routes.php';

try {
    $router->matchRoute();
} catch (NotFoundException $e) {
    ErrorHandler::displayFullScreenException($e);
    exit;
} catch (UnauthorizedAccessException $e) {
    ErrorHandler::displayFullScreenException($e);
    exit;
}