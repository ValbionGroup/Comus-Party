<?php

/**
 * @brief Fichier gérant les exceptions personnalisées de l'application
 *
 * @file CustomExceptions.php
 * @author Lucas ESPIET "lespiet@iutbayonne.univ-pau.fr"
 * @version 1.0
 * @date 2024-11-12
 */

namespace models;

use Exception;
use Throwable;

/**
 * @brief Classe RouteNotFoundException
 * @details Exception personnalisée levée lorsqu'une route n'est pas trouvée
 * @extends Exception
 */
class RouteNotFoundException extends Exception {
    public function __construct($message, $code = 404, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [$this->code]: $this->message\n";
    }

}

/**
 * @brief Classe ControllerNotFoundException
 * @details Exception personnalisée levée lorsqu'un contrôleur n'est pas trouvé
 * @extends Exception
 */
class ControllerNotFoundException extends Exception {
        public function __construct($message, $code = 500, Throwable $previous = null) {
            parent::__construct($message, $code, $previous);
        }

        public function __toString() {
            return __CLASS__ . ": [$this->code]: $this->message\n";
        }
}

/**
 * @brief Classe MethodNotFoundException
 * @details Exception personnalisée levée lorsqu'une méthode n'est pas trouvée
 * @extends Exception
 */
class MethodNotFoundException extends Exception
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

/**
 * @brief Classe UnauthorizedAccessException
 * @details Exception personnalisée levée lorsqu'un utilisateur n'est pas autorisé à accéder à une ressource
 * @extends Exception
 */
class UnauthorizedAccessException extends Exception {
    public function __construct($message, $code = 403, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [$this->code]: $this->message\n";
    }
}