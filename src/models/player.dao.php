<?php
/**
 * @file    player.dao.php
 * @author  Estéban DESESSARD
 * @brief   Le fichier contient la déclaration & définition de la classe PlayerDAO.
 * @date    12/11/2024
 * @version 0.1
 */

 use Ramsey\Uuid\Uuid; // Pour la création de l'uuid

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
        $player->setComusCoin($data['comus_coin']);
        $player->getStatistics()->setPlayerUuid($data['uuid'] ?? null);
        $player->getStatistics()->setGamesPlayed($data['games_played'] ?? null);
        $player->getStatistics()->setGamesWon($data['games_won'] ?? null);
        $player->getStatistics()->setGamesHosted($data['games_hosted'] ?? null);
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

    
    /**
     * @brief Crée un nouveau joueur dans la base de données
     * 
     * @details Cette méthode génère un UUID unique pour le joueur, récupère l'identifiant utilisateur à partir de l'adresse e-mail,
     * et insère un nouvel enregistrement dans la table des joueurs avec l'UUID, le nom d'utilisateur, et l'ID utilisateur.
     * 
     * @param string $username Le nom d'utilisateur du joueur
     * @param string $email L'adresse e-mail liée au joueur
     * @return bool Retourne true si le joueur a été créé avec succès, false sinon
     */
    public function createPlayer(string $username, string $email): bool
    {
        // Genération de l'uuid du joueur
        $uuid = Uuid::uuid4()->toString();

        $userId=PlayerDAO::getUserIdByEmail($email);

        $stmtPlayer = $this->pdo->prepare("INSERT INTO " . DB_PREFIX . "player (uuid, username, user_id) VALUES (:uuid, :username, :user_id)");

        $stmtPlayer->bindValue(':uuid', $uuid);
        $stmtPlayer->bindValue(':username', $username);
        $stmtPlayer->bindValue(':user_id', $userId);
        
        return $stmtPlayer->execute();
    }

    /**
     * @brief Retourne un objet Player (ou null) à partir du nom d'utilisateur passé en paramètre
     * @param string|null $username Le nom d'utilisateur du joueur à retrouver
     * @return Player|null Objet retourné par la méthode, ici un joueur (ou null si non-trouvé)
     */
    public function findByUsername(?string $username): ?Player {
        $stmt = $this->pdo->prepare("SELECT * FROM " . DB_PREFIX . "player WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetch();
        if ($result === false) {
            return null;
        }
        return $this->hydrate($result);
    }

    /**
     * @brief Retourne l'identifiant utilisateur associé à l'adresse e-mail
     * @param string $email L'adresse e-mail dont l'ID utilisateur est recherché
     * @return int L'identifiant utilisateur lié à l'adresse e-mail
     */
    public function getUserIdByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT u.id
                                    FROM " . DB_PREFIX . "user u
                                    WHERE u.email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['id'];
    }
}