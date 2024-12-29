<?php
/**
 * @brief Gère les exceptions lorsqu'une route n'est pas trouvée
 * @file RouteNotFoundException.php
 * @author Lucas ESPIET "lespiet@iutbayonne.univ-pau.fr"
 * @version 1.0
 * @date 2024-11-20
 */

namespace ComusParty\App\Exceptions;

use Throwable;

/**
 * @brief Classe RouteNotFoundException
 * @details Exceptions personnalisée levée lorsqu'une route n'est pas trouvée
 */
class RouteNotFoundException extends NotFoundException
{
    public function __construct($message, $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return __CLASS__ . ": [$this->code]: $this->message\n";
    }
}