<?php

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * @param Exception $exception
 * @return void
 * @throws LoaderError
 * @throws RuntimeError
 * @throws SyntaxError
 */
function displayError(Exception $exception): void
{
    global $twig;
    $template = $twig->load('errors.twig');
    echo $template->render([
        'error' => $exception->getCode() ?? 500,
        'message' => $exception->getMessage() ?? 'Une erreur interne est survenue'
    ]);
    die;
}