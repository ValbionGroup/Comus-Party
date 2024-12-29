<?php
/**
 * @brief Fichier contenant la déclaration et la définition de l'exception GameSettingsException
 *
 * @file GameSettingsException.php
 * @author Lucas ESPIET "espiet.l@valbion.com"
 * @version 1.0
 * @date 2024-12-17
 */

namespace ComusParty\App\Exceptions;

use Exception;

/**
 * @brief Classe GameSettingsException
 * @details Exceptions levée lorsqu'une erreur survient lors de la configuration d'une partie
 */
class GameSettingsException extends Exception
{
    /**
     * @brief Constructeur de la classe GameSettingsException
     * @param string $message Message d'erreur
     * @param int $code Code d'erreur
     * @param Exception|null $previous Exceptions précédente
     */
    public function __construct(string $message = "", int $code = 500, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @brief Méthode permettant d'afficher le message d'erreur
     * @return string Message d'erreur
     */
    public function __toString(): string
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}