<?php

/**
 * La classe PlayerDAO permet de faire des opérations sur la table player
 */
class PlayerDAO {
    /**
     * Classe PDO pour la connexion à la base de données
     *
     * @var PDO|null
     */
    private ?PDO $pdo;

    /**
     * Constructeur de la classe PlayerDAO
     *
     * @param PDO|null $pdo
     */
    public function __construct(?PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Retourne la connexion à la base de données
     *
     * @return PDO|null
     */
    public function getPdo(): ?PDO
    {
        return $this->pdo;
    }

    /**
     * Modifie la connexion à la base de données
     *
     * @param PDO|null $pdo
     */
    public function setPdo(?PDO $pdo): void
    {
        $this->pdo = $pdo;
    }

    /**
     * Retourne un objet Player (ou null) à partir de l'UUID passé en paramètre
     *
     * @param string $uuid
     * @return Player|null
     */
    public function findByUuid(string $uuid): ?Player {
        $stmt = $this->pdo->prepare('SELECT * FROM player WHERE uuid = :uuid');
        $stmt->bindParam(':uuid', $uuid);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Player');
        $player = $stmt->fetch();
        return $player;
    }
}