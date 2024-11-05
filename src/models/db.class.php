<?php

/**
 * La classe Bd est un singleton qui permet de se connecter à la base de données
 */
class Db {
    /**
     * Instance du singleton de la base de données
     *
     * @var Db|null
     */
    private static ?Db $instance = null;

    /**
     * Classe PDO pour la connexion à la base de données
     *
     * @var PDO|null
     */
    private ?PDO $pdo;

    /**
     * Contructeur de la classe Bd
     */
    private function __construct() {
        try {
            $this->pdo = new PDO(DB_TYPE.':host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    /**
     * Retourne l'instance de la classe Bd
     *
     * @return Db
     */
    public static function getInstance(): Db {
        if (self::$instance == null) {
            self::$instance = new Db();
        }
        return self::$instance;
    }

    /**
     * Retourne la connexion à la base de données
     *
     * @return PDO
     */
    public function getConnection(): PDO {
        return $this->pdo;
    }

    /**
     * Surcharge de la méthode __clone pour empêcher le clonage de l'objet
     *
     * @return void
     */
    private function __clone() {}

    /**
     * Surcharge de la méthode __wakeup pour empêcher la désérialisation de l'objet
     *
     * @return mixed
     * @throws Exception
     */
    public function __wakeup() {
        throw new Exception("Cannot unserialize a singleton.");
    }
}