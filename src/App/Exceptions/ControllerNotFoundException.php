<?php
/**
 * @brief Gère les exceptions lorsqu'un contôleur n'est pas trouvé
 * @file ControllerNotFoundException.php
 * @author Lucas ESPIET "lespiet@iutbayonne.univ-pau.fr"
 * @version 1.0
 * @date 2024-11-20
 */

namespace ComusParty\App\Exceptions;

use Throwable;

/**
 * @brief Classe ControllerNotFoundException
 * @details Exception personnalisée levée lorsqu'un contrôleur n'est pas trouvé
 */
class ControllerNotFoundException extends NotFoundException
{
    public function __construct($message, $code = 500, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return __CLASS__ . ": [$this->code]: $this->message\n";
    }
}