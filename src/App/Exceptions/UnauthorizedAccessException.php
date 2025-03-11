<?php
/**
 * @brief Gère les exceptions lorsqu'une méthode n'est pas trouvée n'est pas existante
 * @file UnauthorizedAccessException.php
 * @author Lucas ESPIET "lespiet@iutbayonne.univ-pau.fr"
 * @version 1.0
 * @date 2024-11-21
 */

namespace ComusParty\App\Exceptions;

use Exception;
use Throwable;

/**
 * @brief Classe UnauthorizedAccessException
 * @details Exception personnalisée levée lorsqu'un utilisateur n'est pas autorisé à accéder à une ressource
 */
class UnauthorizedAccessException extends Exception
{
    public function __construct($message, $code = 403, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return __CLASS__ . ": [$this->code]: $this->message\n";
    }
}