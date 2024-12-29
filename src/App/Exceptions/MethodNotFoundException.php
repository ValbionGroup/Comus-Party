<?php
/**
 * @brief Gère les exceptions lorsqu'une méthode n'est pas trouvée/n'est pas existante
 * @file MethodNotFoundException.php
 * @author Lucas ESPIET "lespiet@iutbayonne.univ-pau.fr"
 * @version 1.0
 * @date 2024-11-21
 */

namespace ComusParty\App\Exceptions;

use Throwable;

/**
 * @brief Classe MethodNotFoundException
 * @details Exceptions personnalisée levée lorsqu'une méthode n'est pas trouvée
 */
class MethodNotFoundException extends NotFoundException
{
    public function __construct(string $message = "", int $code = 500, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return __CLASS__ . ": [$this->code]: $this->message\n";
    }
}