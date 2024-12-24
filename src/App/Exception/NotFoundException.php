<?php
/**
 * @brief Gère les exceptions quand quelque chose n'est pas trouvé
 * @file NotFoundException.php
 * @author Lucas ESPIET "lespiet@iutbayonne.univ-pau.fr"
 * @version 1.0
 * @date 2024-11-20
 */

namespace ComusParty\App\Exception;

use Exception;
use Throwable;

/**
 * @brief Classe NotFoundException
 * @details La classe NotFoundException permet de gérer les exceptions de type 404
 */
class NotFoundException extends Exception
{
    public function __construct(string $message = "", int $code = 404, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return __CLASS__ . ": [$this->code]: $this->message\n";
    }
}