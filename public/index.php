<?php

global $router;

use ComusParty\App\MessageHandler;
use ComusParty\App\Exception\NotFoundException;
use ComusParty\App\Exception\UnauthorizedAccessException;

require __DIR__ . '/../include.php';
require __DIR__ . '/../src/routes.php';

try {
    $router->matchRoute();
} catch (NotFoundException|UnauthorizedAccessException $e) {
    MessageHandler::displayFullScreenException($e);
    exit;
}