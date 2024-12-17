<?php
/**
 * @brief Gestion de l'affichage des erreurs
 *
 * @file MessageHandler.php
 * @author Lucas ESPIET "espiet.l@valbion.com"
 * @version 1.1
 * @date 2024-11-21
 */

namespace ComusParty\Models\Exception;

use Exception;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * @brief Gestion de l'affichage des erreurs
 * @details La classe MessageHandler permet de gérer l'affichage des erreurs de l'application
 */
class MessageHandler
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
     * Ajout les données d'une erreur en variable de session
     *
     * @param Exception $exception
     * @return void
     */
    public static function addExceptionParametersToSession(Exception $exception): void
    {
        $_SESSION['error'] = [
            'code' => $exception->getCode() ?? 500,
            'message' => $exception->getMessage() ?? 'Une erreur interne est survenue'
        ];
    }

    /**
     * Ajout les données d'un message en variable de session
     *
     * @param string $message Message à afficher
     * @return void
     */
    public static function addMessageParametersToSession(string $message): void
    {
        $_SESSION['success'] = $message;
    }
}