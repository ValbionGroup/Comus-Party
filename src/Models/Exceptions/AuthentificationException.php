<?php
/**
 * @brief Gère les exceptions d'authentification
 * @file AuthentificationException.php
 * @author Lucas ESPIET "lespiet@iutbayonne.univ-pau.fr"
 * @version 1.0
 * @date 2024-11-21
 */

namespace ComusParty\Models\Exception;

use Exception;
use Throwable;

/**
 * @brief Classe AuthentificationException
 * @details La classe AuthentificationException permet de gérer les exceptions liées à l'authentification
 */
class AuthentificationException extends Exception
{
    public function __construct(string $message = "", int $code = 401, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return __CLASS__ . ": [$this->code]: $this->message\n";
    }
}