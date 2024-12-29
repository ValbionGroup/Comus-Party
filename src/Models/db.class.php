<?php
/**
 * @file    db.class.php
 * @author  Estéban DESESSARD
 * @brief   Le fichier contient la déclaration & définition de la classe Db.
 * @date    12/11/2024
 * @version 0.1
 */

namespace ComusParty\Models;

use Exception;
use PDO;
use PDOException;

/**
 * @brief Class Db
 * @details La classe Db est un singleton qui permet de se connecter à la base de données
 */
class Db
{
    /**
     * @brief Instance du singleton de la base de données
     * @var Db|null
     */
    private static ?Db $instance = null;

    /**
     * @brief La connexion à la base de données
     * @var PDO|null
     */
    private ?PDO $pdo;

    /**
     * @biref Contructeur de la classe Bd
     */
    private function __construct()
    {
        try {
            $this->pdo = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    /**
     * @brief Retourne l'instance du singleton de la base de données
     * @return Db Objet retourné par la méthode, ici l'instance du singleton de la base de données
     */
    public static function getInstance(): Db
    {
        if (self::$instance == null) {
            self::$instance = new Db();
        }
        return self::$instance;
    }

    /**
     * @brief Retourne la connexion à la base de données
     * @return PDO Objet retourné par la méthode, ici un PDO représentant la connexion à la base de données
     */
    public function getConnection(): PDO
    {
        return $this->pdo;
    }

    /**
     * @brief Surcharge de la méthode afin d'empêcher la désérialisation de l'instance de la classe
     * @throws Exception Exceptions levée dans le cas d'une tentative de désérialisation
     */
    public function __wakeup()
    {
        throw new Exception("Cannot unserialize a singleton.");
    }

    /**
     * @brief Surcharge de la méthode afin d'empêcher la création d'une nouvelle instance de la classe
     */
    private function __clone()
    {
    }
}