<?php

/**
 * @brief L'entrée de l'application
 */

global $router;

use ComusParty\App\Exceptions\NotFoundException;
use ComusParty\App\Exceptions\UnauthorizedAccessException;
use ComusParty\App\MessageHandler;

require __DIR__ . '/../include.php';
require __DIR__ . '/../src/routes.php';

try {
    $router->matchRoute();
} catch (NotFoundException|UnauthorizedAccessException|Exception $e) {
    MessageHandler::displayFullScreenException($e);
    exit;
}