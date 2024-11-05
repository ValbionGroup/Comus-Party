<?php

class Bd {
    private static ?Bd $instance = null;
    private ?PDO $pdo;

    private function __construct() {
        try {
            $this->pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    public static function getInstance(): Bd {
        if (self::$instance == null) {
            self::$instance = new Bd();
        }
        return self::$instance;
    }

    public function getConnection(): PDO {
        return $this->pdo;
    }

    private function __clone() {}

    private function __wakeup() {
        throw new Exception("Cannot unserialize a singleton.");
    }
}