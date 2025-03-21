<?php
/**
 * @brief Fichier de déclaration et définition de la classe MessageHandler.
 * @details La classe MessageHandler permet de gérer l'affichage des erreurs de l'application
 *
 * @file MessageHandler.php
 * @author Lucas ESPIET "espiet.l@valbion.com"
 * @version 1.2
 * @date 2024-11-21
 */

namespace ComusParty\App;

use Error;
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
     * @brief Lève une erreur bloquante.
     *
     * @param Exception $exception Exception à afficher
     * @return void
     * @throws LoaderError Erreur de chargement de template
     * @throws RuntimeError Erreur d'exécution de template
     * @throws SyntaxError Erreur de syntaxe de template
     */
    public static function displayFullScreenException(Exception $exception): void
    {
        global $twig;
        $template = $twig->load('errors.twig');
        http_response_code(is_int($exception->getCode()) ? $exception->getCode() : 500);
        echo $template->render([
            'error' => $exception->getCode() ?? 500,
            'message' => $exception->getMessage() ?? 'Une erreur interne est survenue'
        ]);
        die;
    }

    /**
     * @brief Ajout les données d'une erreur en variable de session
     *
     * @param Exception $exception Exception à afficher
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
     * @brief Ajout les données d'un message en variable de session
     *
     * @param string $message Message à afficher
     * @return void
     */
    public static function addMessageParametersToSession(string $message): void
    {
        $_SESSION['success'] = $message;
    }

    /**
     * @brief Lève une erreur bloquante.
     *
     * @param Error $error Erreur à afficher
     * @return void
     * @throws LoaderError Erreur de chargement de template
     * @throws RuntimeError Erreur d'exécution de template
     * @throws SyntaxError Erreur de syntaxe de template
     */
    public static function displayFullScreenError(Error $error): void
    {
        global $twig;
        $template = $twig->load('errors.twig');
        http_response_code(500);
        echo $template->render([
            'error' => ($error->getCode() == 0) ? 500 : $error->getCode(),
            'message' => $error->getMessage() ?? 'Une erreur interne est survenue'
        ]);
        die;
    }

    /**
     * @brief Envoie une erreur en JSON et interrompt l'exécution
     * @param Exception|Error $e Erreur à afficher
     * @return void
     */
    public static function sendJsonException(Exception|Error $e): void
    {
        http_response_code(($e->getCode() == 0) ? 500 : $e->getCode());
        echo json_encode([
            'success' => false,
            'error' => ($e->getCode() == 0) ? 500 : $e->getCode(),
            'message' => $e->getMessage() ?? 'Une erreur interne est survenue'
        ]);
        die;
    }

    /**
     * @brief Envoie une erreur custom en JSON et interrompt l'exécution
     * @param int $code Code d'erreur
     * @param string $message Message d'erreur
     * @param array|null $params Paramètres supplémentaires
     * @return void
     */
    public static function sendJsonCustomException(int $code, string $message, ?array $params = []): void
    {
        http_response_code($code);
        echo json_encode([
            'success' => false,
            'error' => $code,
            'message' => $message,
            ...$params
        ]);
        die;
    }

    /**
     * @brief Envoie un message en JSON
     * @param string $message Message à afficher
     * @param array|null $params Paramètres supplémentaires
     * @return string Message en JSON
     */
    public static function sendJsonMessage(string $message, ?array $params = []): string
    {
        return json_encode([
            'success' => true,
            'message' => $message,
            ...$params
        ]);
    }
}