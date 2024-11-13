<?php
/**
 * La classe GameDAO permet de faire des opérations sur la table game
 */
class GameDao
{
    /**
     * Classe PDO pour la connexion à la base de données
     *
     * @var PDO|null
     */
    private ?PDO $pdo;

    /**
     * Constructeur de la classe GameDAO
     *
     * @param PDO|null $pdo
     */
    public function __construct(?PDO $pdo){
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
     * Retourne un objet Game (ou null) à partir de l'identifiant passé en paramètre
     *
     * @param int $id
     * @return Game|null
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
     * Retourne un tableau d'objets Game à partir de la table game
     *
     * @return array
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
     * Retourne l'état du jeu en type Etat à partir d'un état de type string
     *
     * @param string $state
     * @return ?State
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
     * Retourne un tableau d'objets Game à partir de la table game
     *
     * @return array
     */
    public function hydrate(array $gameTab): Game{
        $game = new Game();
        $game->setId($gameTab['id']);
        $game->setName($gameTab['name']);
        $game->setDescription($gameTab['description']);
        $game->setPathImg($gameTab['img_path']);
        $game->setState($this->transformState($gameTab['state']));
        $game->setCreatedAt(new DateTime($gameTab['created_at']));
        $game->setUpdatedAt(new DateTime($gameTab['updated_at']));
        return $game;
    }

    /**
     * Retourne un tableau d'objets Game à partir de la table game
     *
     * @param array $gamesTab
     * @return array
     */
    public function hydrateMany(array $gamesTab): array{
        $games = [];
        foreach ($gamesTab as $gameTab) {
            $games[] = $this->hydrate($gameTab);
        }
        return $games;
    }

}