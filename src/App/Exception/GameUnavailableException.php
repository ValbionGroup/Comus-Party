<?php
/**
 * @brief Gère les exceptions liées à l'indisponibilité d'un jeu
 *
 * @file GameUnavailableException.php
 * @author Lucas ESPIET "espiet.l@valbion.com"
 * @version 1.0
 * @date 2024-12-16
 */

namespace ComusParty\App\Exception;

use Exception;
use Throwable;

/**
 * @brief Classe GameUnavailableException
 * @details Exception personnalisée levée lorsqu'un jeu est indisponible
 */
class GameUnavailableException extends Exception
{
    public function __construct(string $message = "", int $code = 503, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return __CLASS__ . ": [$this->code]: $this->message\n";
    }
}