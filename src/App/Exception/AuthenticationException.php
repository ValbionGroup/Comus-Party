<?php
/**
 * @brief Gère les exceptions d'authentification
 * @file AuthenticationException.php
 * @author Lucas ESPIET "lespiet@iutbayonne.univ-pau.fr"
 * @version 1.0
 * @date 2024-11-21
 */

namespace ComusParty\App\Exception;

use Exception;
use Throwable;

/**
 * @brief Classe AuthenticationException
 * @details La classe AuthenticationException permet de gérer les exceptions liées à l'authentification
 */
class AuthenticationException extends Exception
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