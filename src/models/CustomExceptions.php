<?php

namespace models;

use Exception;
use Throwable;

class RouteNotFoundException extends Exception {
    public function __construct($message, $code = 404, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [$this->code]: $this->message\n";
    }

}

class ControllerNotFoundException extends Exception {
        public function __construct($message, $code = 500, Throwable $previous = null) {
            parent::__construct($message, $code, $previous);
        }

        public function __toString() {
            return __CLASS__ . ": [$this->code]: $this->message\n";
        }
}

class UnauthorizedAccessException extends Exception {
    public function __construct($message, $code = 403, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [$this->code]: $this->message\n";
    }
}