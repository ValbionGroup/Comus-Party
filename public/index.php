<?php

global $router;

use ComusParty\Models\Exception\MessageHandler;
use ComusParty\Models\Exception\NotFoundException;
use ComusParty\Models\Exception\UnauthorizedAccessException;

require __DIR__.'/../include.php';

require __DIR__.'/../src/routes.php';

try {
    $router->matchRoute();
} catch (NotFoundException $e) {
    MessageHandler::displayFullScreenException($e);
    exit;
} catch (UnauthorizedAccessException $e) {
    MessageHandler::displayFullScreenException($e);
    exit;
}