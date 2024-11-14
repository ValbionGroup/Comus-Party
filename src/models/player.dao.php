<?php
/**
 * @file    player.dao.php
 * @author  Estéban DESESSARD
 * @brief   Le fichier contient la déclaration & définition de la classe PlayerDAO.
 * @date    12/11/2024
 * @version 0.1
 */


/**
 * @brief Classe PlayerDAO
 * @details La classe PlayerDAO permet de faire des opérations sur la table player dans la base de données
 */
class PlayerDAO {
    /**
     * @brief La connexion à la base de données
     * @var PDO|null
     */
    private ?PDO $pdo;

    /**
     * @brief Le constructeur de la classe PlayerDAO
     * @param PDO|null $pdo La connexion à la base de données
     */
    public function __construct(?PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @brief Retourne la connexion à la base de données
     * @return PDO|null Objet retourné par la méthode, ici un PDO représentant la connexion à la base de données
     */
    public function getPdo(): ?PDO
    {
        return $this->pdo;
    }

    /**
     * @brief Modifie la connexion à la base de données
     * @param PDO|null $pdo La nouvelle connexion à la base de données
     */
    public function setPdo(?PDO $pdo): void
    {
        $this->pdo = $pdo;
    }

    /**
     * @brief Retourne un objet Player (ou null) à partir de l'UUID passé en paramètre
     * @param string $uuid L'UUID du joueur recherché
     * @return Player|null Objet retourné par la méthode, ici un joueur (ou null si non-trouvé)
     */
    public function findByUuid(string $uuid): ?Player {
        $stmt = $this->pdo->prepare(
            'SELECT *
            FROM '. DB_PREFIX .'player
            WHERE uuid = :uuid');
        $stmt->bindParam(':uuid', $uuid);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $playerTab = $stmt->fetch();
        if ($playerTab === false) {
            return null;
        }
        $player = $this->hydrate($playerTab);
        return $player;
    }

    /**
     * @brief Retourne un objet Player (ou null) à partir de l'identifiant utilisateur passé en paramètre
     * @param int $userId L'identifiant utilisateur recherché
     * @return Player|null Objet retourné par la méthode, ici un joueur (ou null si non-trouvé)
     */
    public function findByUserId(int $userId): ?Player
    {
        $stmt = $this->pdo->prepare(
            'SELECT *
            FROM '. DB_PREFIX .'player
            WHERE user_id = :userId');
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $playerTab = $stmt->fetch();
        if ($playerTab === false) {
            return null;
        }
        $player = $this->hydrate($playerTab);
        return $player;
    }

    /**
     * @brief Retourne un objet Player (ou null) à partir de l'UUID passé en paramètre avec les détails de l'utilisateur associé
     * @param string $uuid L'UUID du joueur recherché
     * @return Player|null Objet retourné par la méthode, ici un joueur (ou null si non-trouvé)
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
     */
    public function findWithDetailByUuid(string $uuid): ?Player {
        $stmt = $this->pdo->prepare(
            'SELECT pr.*, u.email, u.created_at, u.updated_at, COUNT(pd.player_uuid) as games_played, COUNT(w.player_uuid) as games_won, COUNT(gr.hosted_by) as games_hosted
            FROM '. DB_PREFIX .'player pr
            JOIN '. DB_PREFIX .'user u ON pr.user_id = u.id
            LEFT JOIN '. DB_PREFIX .'played pd ON pr.uuid = pd.player_uuid
            LEFT JOIN '. DB_PREFIX .'winned w ON pr.uuid = w.player_uuid
            LEFT JOIN ' . DB_PREFIX . 'game_record gr ON pr.uuid = gr.hosted_by
            WHERE pr.uuid = :uuid');
        $stmt->bindParam(':uuid', $uuid);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $tabPlayer = $stmt->fetch();
        if ($tabPlayer === false || $tabPlayer['uuid'] === null) {
            return null;
        }
        $player = $this->hydrate($tabPlayer);
        return $player;
    }

    /**
     * @brief Retourne un objet Player (ou null) à partir de l'identifiant utilisateur passé en paramètre avec les détails de l'utilisateur associé
     * @param int $userId L'identifiant utilisateur recherché
     * @return Player|null Objet retourné par la méthode, ici un joueur (ou null si non-trouvé)
     */
    public function findWithDetailByUserId(int $userId): ?Player {
        $stmt = $this->pdo->prepare(
            'SELECT pr.*, u.email, u.created_at, u.updated_at, COUNT(pd.player_uuid) as games_played, COUNT(w.player_uuid) as games_won, COUNT(gr.hosted_by) as games_hosted
            FROM ' . DB_PREFIX . 'player pr
            JOIN ' . DB_PREFIX . 'user u ON pr.user_id = u.id
            LEFT JOIN ' . DB_PREFIX . 'played pd ON pr.uuid = pd.player_uuid
            LEFT JOIN ' . DB_PREFIX . 'winned w ON pr.uuid = w.player_uuid
            LEFT JOIN ' . DB_PREFIX . 'game_record gr ON pr.uuid = gr.hosted_by
            WHERE u.id = :userId');
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
     * @brief Retourne un tableau d'objets Player recensant l'ensemble des joueurs enregistrés dans la base de données
     * @return array|null Objet retourné par la méthode, ici un tableau d'objets Player (ou null si aucune joueur recensé)
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
     * @warning Cette méthode retourne un tableau contenant autant d'objet qu'il y a de joueurs dans la base de données, pouvant ainsi entraîner la manipulation d'un grand set de données.
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
     * @brief Retourne un tableau d'objets Player recensant l'ensemble des joueurs enregistrés dans la base de données avec les détails de l'utilisateur associé
     * @return array|null Objet retourné par la méthode, ici un tableau d'objets Player (ou null si aucune joueur recensé)
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
     * @warning Cette méthode retourne un tableau contenant autant d'objet qu'il y a de joueurs dans la base de données, pouvant ainsi entraîner la manipulation d'un grand set de données.
     */
    public function findAllWithDetail() : ?array {
        $stmt = $this->pdo->query(
            'SELECT pr.*, u.email, u.created_at, u.updated_at, COUNT(pd.player_uuid) as games_played, COUNT(w.player_uuid) as games_won, COUNT(gr.hosted_by) as games_hosted
            FROM ' . DB_PREFIX . 'player pr
            JOIN ' . DB_PREFIX . 'user u ON pr.user_id = u.id
            LEFT JOIN ' . DB_PREFIX . 'played pd ON pr.uuid = pd.player_uuid
            LEFT JOIN ' . DB_PREFIX . 'winned w ON pr.uuid = w.player_uuid
            LEFT JOIN ' . DB_PREFIX . 'game_record gr ON pr.uuid = gr.hosted_by');
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $tabPlayers = $stmt->fetchAll();
        if ($tabPlayers === false) {
            return null;
        }
        $players = $this->hydrateMany($tabPlayers);
        return $players;
    }

    /**
     * @brief Hydrate un objet Player avec les valeurs du tableau associatif passé en paramètre
     * @param array $data Le tableau associatif content les paramètres
     * @return Player L'objet retourné par la méthode, ici un joueur
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
     */
    public function hydrate(array $data) : Player {
        $player = new Player();
        $player->setStatistics(new Statistics());
        $player->setUuid($data['uuid']);
        $player->setUsername($data['username']);
        $player->setCreatedAt(new DateTime($data['created_at']));
        $player->setUpdatedAt(new DateTime($data['updated_at']));
        $player->setXp($data['xp']);
        $player->setElo($data['elo']);
        $player->setComusCoins($data['comus_coin']);
        $player->getStatistics()->setPlayerUuid($data['uuid']);
        $player->getStatistics()->setGamesPlayed($data['games_played']);
        $player->getStatistics()->setGamesWon($data['games_won']);
        $player->getStatistics()->setGamesHosted($data['games_hosted']);
        $player->setUserId($data['user_id']);
        return $player;
    }

    /**
     * @brief Hydrate un tableau d'objets Player avec les valeurs des tableaux associatifs du tableau passé en paramètre
     * @details Cette méthode appelle, pour chaque tableau associatif contenu dans celui passé en paramètre, la méthode hydrate() définie ci-dessus.
     * @param array $data Le tableau de tableaux associatifs
     * @return array L'objet retourné par la méthode, ici un tableau (d'objets Player)
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
     */
    public function hydrateMany(array $data) : array {
        $players = [];
        foreach ($data as $player) {
            $players[] = $this->hydrate($player);
        }
        return $players;
    }
}