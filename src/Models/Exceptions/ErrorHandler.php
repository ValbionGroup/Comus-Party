<?php
/**
 * @brief Gestion de l'affichage des erreurs
 *
 * @file ErrorHandler.php
 * @author Lucas ESPIET "espiet.l@valbion.com"
 * @version 1.0
 * @date 2024-11-21
 */

namespace ComusParty\Models\Exception;

use Exception;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * @brief Gestion de l'affichage des erreurs
 * @details La classe ErrorHandler permet de gérer l'affichage des erreurs de l'application
 */
class ErrorHandler
{
    /**
     * Lève une erreur bloquante.
     *
     * @param Exception $exception
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public static function displayFullScreenException(Exception $exception): void
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

    /**
     * Ajout les données d'une erreur en variable globale Twig
     *
     * @param Exception $exception
     * @return void
     */
    public static function addExceptionParametersToTwig(Exception $exception): void
    {
        global $twig;
        $twig->addGlobal('error', [
            'code' => $exception->getCode() ?? 500,
            'message' => $exception->getMessage() ?? 'Une erreur interne est survenue'
        ]);
    }
}