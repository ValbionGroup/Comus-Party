<?php
/**
 * @file game.dao.php
 * @brief Le fichier contient la déclaration et la définition de la classe GameDao
 * @author Conchez-Boueytou Robin
 * @date 13/11/2024
 * @version 0.1
 */

/**
 * @brief Classe GameDao
 * @details La classe GameDao permet de faire des opérations sur la table game de la base de données
 */
class GameDao
{
    /**
     * Classe PDO pour la connexion à la base de données
     *
     * @var PDO|null La connexion à la base de données
     */
    private ?PDO $pdo;

    /**
     * @brief Constructeur de la classe GameDAO
     *
     * @param PDO|null $pdo La connexion à la base de données
     */
    public function __construct(?PDO $pdo){
        $this->pdo = $pdo;
    }

    /**
     * @brief Retourne la connexion à la base de données
     *
     * @return PDO|null La connexion à la base de données
     */
    public function getPdo(): ?PDO
    {
        return $this->pdo;
    }

    /**
     * @brief Modifie la connexion à la base de données
     *
     * @param PDO|null $pdo La nouvelle connexion à la base de données
     */
    public function setPdo(?PDO $pdo): void
    {
        $this->pdo = $pdo;
    }

    /**
     * @brief Retourne un objet Game (ou null) à partir de l'identifiant passé en paramètre
     * @details
     *
     * @param int $id L'identifiant du jeu
     * @return Game|null L'objet Game correspondant à l'identifiant ou null
     */
    public function findById(int $id): ?Game{
        $stmt = $this->pdo->prepare(
            'SELECT *
            FROM '. DB_PREFIX .'game
            WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $gameTab = $stmt->fetch();
        if ($gameTab === false) {
            return null;
        }
        return $this->hydrate($gameTab);
    }

    /**
     * @brief Retourne un tableau d'objets Game à partir de la table game
     *
     * @return array Le tableau d'objets Game
     */
    public function findAll(): array{
        $stmt = $this->pdo->prepare(
            'SELECT *
            FROM '. DB_PREFIX .'game');
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $gamesTab = $stmt->fetchAll();
        return $this->hydrateMany($gamesTab);
    }

    /**
     * @brief Retourne l'état du jeu en type Etat à partir d'un état de type string
     *
     * @param string $state L'état du jeu
     * @return ?State L'état du jeu en type State
     */
    public function transformState(string $state):?State{
        return match (strtoupper($state)) {
            'AVAILABLE' => State::AVAILABLE,
            'UNVAILABLE' => State::UNVAILABLE,
            'MAINTENANCE' => State::MAINTENANCE,
            default => null,
        };
    }

    /**
     * @brief Hydrate un d'objet Game à partir d'un tableau de jeux de la table game passé en paramètre
     *
     * @return Game Un objet Game
     */
    public function hydrate(array $gameTab): Game{
        $game = new Game();
        $game->setId($gameTab['id']);
        $game->setName($gameTab['name']);
        $game->setDescription($gameTab['description']);
        $game->setPathImg($gameTab['path_img']);
        $game->setState($this->transformState($gameTab['state']));
        $game->setCreatedAt(new DateTime($gameTab['created_at']));
        $game->setUpdatedAt(new DateTime($gameTab['updated_at']));
        return $game;
    }

    /**
     * @brief Hydrate un tableau d'objets Game à partir de la table game
     * @details Cette méthode appelle, pour chaque jeu du tableau, la méthode hydrate
     * @param array $gamesTab Le tableau de jeux
     * @return array Le tableau d'objets Game
     */
    public function hydrateMany(array $gamesTab): array{
        $games = [];
        foreach ($gamesTab as $gameTab) {
            $games[] = $this->hydrate($gameTab);
        }
        return $games;
    }

}