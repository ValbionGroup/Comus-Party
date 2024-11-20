<?php

namespace ComusParty\Models;

use Exception;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Lève une erreur bloquante.
 *
 * @param Exception $exception
 * @return void
 * @throws LoaderError
 * @throws RuntimeError
 * @throws SyntaxError
 */
function displayFullScreenError(Exception $exception): void
{
    global $twig;
    $template = $twig->load('errors.twig');
    http_response_code($exception->getCode() ?? 500);
    echo $template->render([
        'error' => $exception->getCode() ?? 500,
        'message' => $exception->getMessage() ?? 'Une erreur interne est survenue'
    ]);
    die;
}

function displayPopUpError(Exception $exception): void
{
    global $twig;
    $twig->addGlobal('error', [
        'code' => $exception->getCode() ?? 500,
        'message' => $exception->getMessage() ?? 'Une erreur interne est survenue'
    ]);
}