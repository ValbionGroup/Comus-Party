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
        $stmt = $this->pdo->prepare(
            'SELECT *
            FROM '. DB_PREFIX .'player
            WHERE uuid = :uuid');
        $stmt->bindParam(':uuid', $uuid);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Player');
        $player = $stmt->fetch();
        if ($player === false) {
            return null;
        }
        return $player;
    }

    /**
     * Retourne un objet Player (ou null) à partir de l'identifiant utilisateur passé en paramètre
     *
     * @param int $userId
     * @return Player|null
     */
    public function findByUserId(int $userId): ?Player
    {
        $stmt = $this->pdo->prepare(
            'SELECT *
            FROM '. DB_PREFIX .'player
            WHERE user_id = :userId');
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Player');
        $player = $stmt->fetch();
        if ($player === false) {
            return null;
        }
        return $player;
    }

    /**
     * Retourne un objet Player (ou null) à partir de l'UUID passé en paramètre avec les détails de l'utilisateur associé
     *
     * @param string $uuid
     * @return Player|null
     * @throws DateMalformedStringException
     */
    public function findWithDetailByUuid(string $uuid): ?Player {
        $stmt = $this->pdo->prepare(
            'SELECT p.*, u.email, u.created_at, u.updated_at
            FROM '. DB_PREFIX .'player p
            JOIN '. DB_PREFIX .'user u ON p.user_id = u.id
            WHERE p.uuid = :uuid');
        $stmt->bindParam(':uuid', $uuid);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $tabPlayer = $stmt->fetch();
        if ($tabPlayer === false) {
            return null;
        }
        $player = $this->hydrate($tabPlayer);
        return $player;
    }

    /**
     * Retourne un objet Player (ou null) à partir de l'identifiant utilisateur passé en paramètre avec les détails de l'utilisateur associé
     *
     * @param int $userId
     * @return Player|null
     */
    public function findWithDetailByUserId(int $userId): ?Player {
        $stmt = $this->pdo->prepare(
            'SELECT p.*, u.username, u.email, u.created_at, u.updated_at
            FROM '. DB_PREFIX .'player p
            JOIN '. DB_PREFIX .'user u ON p.user_id = u.id
            WHERE p.user_id = :userId');
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $tabPlayer = $stmt->fetch();
        if ($tabPlayer === false) {
            return null;
        }
        $player = $this->hydrate($tabPlayer);
        return $player;
    }

    /**
     * Retourne un tableau d'objets Player recensant l'ensemble des joueurs enregistrés dans la base de données
     *
     * ⚠️ : Cette méthode retourne un tableau contenant autant d'objet qu'il y a de joueurs dans la base de données, pouvant ainsi entraîner la manipulation d'un grand set de données.
     *
     * @return array
     * @throws DateMalformedStringException
     */
    public function findAll() : ?array {
        $stmt = $this->pdo->query(
            'SELECT *
            FROM '. DB_PREFIX .'player');
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $tabPlayers = $stmt->fetchAll();
        if ($tabPlayers === false) {
            return null;
        }
        $players = $this->hydrateMany($tabPlayers);
        return $players;
    }

    /**
     * Retourne un tableau d'objets Player recensant l'ensemble des joueurs enregistrés dans la base de données avec les détails de l'utilisateur associé
     *
     * ⚠️ : Cette méthode retourne un tableau contenant autant d'objet qu'il y a de joueurs dans la base de données, pouvant ainsi entraîner la manipulation d'un grand set de données.
     *
     * @return array
     * @throws DateMalformedStringException
     */
    public function findAllWithDetail() : ?array {
        $stmt = $this->pdo->query(
            'SELECT p.*, u.username, u.email, u.created_at, u.updated_at
            FROM '. DB_PREFIX .'player p
            JOIN '. DB_PREFIX .'user u ON p.user_id = u.id');
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $tabPlayers = $stmt->fetchAll();
        if ($tabPlayers === false) {
            return null;
        }
        $players = $this->hydrateMany($tabPlayers);
        return $players;
    }

    /**
     * Retourne un objet Player valorisé avec les valeurs du tableau associatif passé en paramètre
     *
     * @param array $data
     * @return Player
     * @throws DateMalformedStringException
     */
    public function hydrate(array $data) : Player {
        $player = new Player();
        $player->setUuid($data['uuid']);
        $player->setUsername($data['username']);
        $player->setCreatedAt(new DateTime($data['created_at']));
        $player->setUpdatedAt(new DateTime($data['updated_at']));
        $player->setXp($data['xp']);
        $player->setElo($data['elo']);
        $player->setComusCoins($data['comus_coins']);
        $player->setUserId($data['user_id']);
        return $player;
    }

    /**
     * Retourne un tableau d'objets Player valorisés avec les valeurs du tableau de tableaux associatifs passé en paramètre
     *
     * @param array $data
     * @return array
     * @throws DateMalformedStringException
     */
    public function hydrateMany(array $data) : array {
        $players = [];
        foreach ($data as $player) {
            $players[] = $this->hydrate($player);
        }
        return $players;
    }
}