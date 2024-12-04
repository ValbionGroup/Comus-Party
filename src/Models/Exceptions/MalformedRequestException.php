<?php
/**
 * @brief Gère les exceptions lorsqu'une requête est mal formée
 * @file MalformedRequestException.php
 * @author Lucas ESPIET "lespiet@iutbayonne.univ-pau.fr"
 * @version 1.0
 * @date 2024-11-21
 */

namespace ComusParty\Models\Exception;

use Exception;
use Throwable;

/**
 * @brief Classe MalformedRequestException
 * @details Exception personnalisée levée lorsqu'une requête est mal formée (ex: paramètres manquants)
 */
class MalformedRequestException extends Exception
{
    public function __construct(string $message = "", int $code = 400, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return __CLASS__ . ": [$this->code]: $this->message\n";
    }
}