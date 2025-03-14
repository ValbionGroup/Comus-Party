<?php
/**
 * @file game.dao.php
 * @brief Fichier de déclaration et définition de la classe GameDAO
 * @author Conchez-Boueytou Robin, ESPIET Lucas
 * @date 13/11/2024
 * @version 0.2
 */

namespace ComusParty\Models;

use DateTime;
use Exception;
use PDO;

/**
 * @brief Classe GameDao
 * @details La classe GameDao permet de faire des opérations sur la table game de la base de données
 */
class GameDAO
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
    public function __construct(?PDO $pdo)
    {
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
     *
     * @param int $id L'identifiant du jeu
     * @return Game|null L'objet Game correspondant à l'identifiant ou null
     * @throws Exception
     */
    public function findById(int $id): ?Game
    {
        $stmt = $this->pdo->prepare(
            'SELECT *
            FROM ' . DB_PREFIX . 'game
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
     * @brief Hydrate un d'objet Game à partir d'un tableau de jeux de la table game passé en paramètre
     *
     * @return Game Un objet Game
     * @throws Exception
     */
    public function hydrate(array $gameTab): Game
    {
        $game = new Game();
        $game->setId($gameTab['id']);
        $game->setName($gameTab['name']);
        $game->setDescription($gameTab['description']);
        $game->setPathImg($gameTab['img_path']);
        $game->setState($this->transformStringToGameState($gameTab['state']));
        $game->setCreatedAt(new DateTime($gameTab['created_at']));
        $game->setUpdatedAt(new DateTime($gameTab['updated_at']));
        $game->setTags($gameTab['tags'] ?? null);
        return $game;
    }

    /**
     * @brief Retourne l'état du jeu en type Etat à partir d'un état de type string
     *
     * @param string $state L'état du jeu
     * @return ?GameState L'état du jeu en type State
     */
    private function transformStringToGameState(string $state): ?GameState
    {
        return match (strtoupper($state)) {
            'AVAILABLE' => GameState::AVAILABLE,
            'UNAVAILABLE' => GameState::UNAVAILABLE,
            'MAINTENANCE' => GameState::MAINTENANCE,
            default => null,
        };
    }

    /**
     * @brief Retourne un tableau d'objets Game à partir de la table game
     *
     * @return array Le tableau d'objets Game
     * @throws Exception Exception levée si un problème survient lors de l'hydratation
     */
    public function findAll(): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT *
            FROM ' . DB_PREFIX . 'game');
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $gamesTab = $stmt->fetchAll();
        return $this->hydrateMany($gamesTab);
    }

    /**
     * @brief Hydrate un tableau d'objets Game à partir de la table game
     * @details Cette méthode appelle, pour chaque jeu du tableau, la méthode hydrate
     * @param array $gamesTab Le tableau de jeux
     * @return array Le tableau d'objets Game
     * @throws Exception Exception levée si un problème survient lors de l'hydratation
     */
    public function hydrateMany(array $gamesTab): array
    {
        $games = [];
        foreach ($gamesTab as $gameTab) {
            $games[] = $this->hydrate($gameTab);
        }
        return $games;
    }

    /**
     * @brief Retourne un tableau d'objets Game à partir de la table game avec leurs tags associés
     * @return array Le tableau d'objets Game
     * @throws Exception Exception levée si un problème survient lors de l'hydratation
     */
    public function findAllWithTags(): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT g.*, GROUP_CONCAT(t.name) as tags
            FROM ' . DB_PREFIX . 'game g
            JOIN ' . DB_PREFIX . 'tagged tg ON g.id = tg.game_id
            JOIN ' . DB_PREFIX . 'tag t ON tg.tag_id = t.id
            GROUP BY g.id;');
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $gamesTab = $stmt->fetchAll();
        $gamesTab = array_map(function ($game) {
            $game['tags'] = explode(',', $game['tags']);
            return $game;
        }, $gamesTab);
        return $this->hydrateMany($gamesTab);
    }

    /**
     * @brief Retourne un objet Game à partir de l'identifiant passé en paramètre avec ses tags associés
     * @param int|null $id L'identifiant du jeu
     * @return Game|null L'objet Game correspondant à l'identifiant ou null
     * @throws Exception Exception levée si un problème survient lors de l'hydratation
     */
    public function findWithDetailsById(?int $id): ?Game
    {
        $stmt = $this->pdo->prepare(
            'SELECT g.*, GROUP_CONCAT(t.name) as tags
            FROM ' . DB_PREFIX . 'game g
            JOIN ' . DB_PREFIX . 'tagged tg ON g.id = tg.game_id
            JOIN ' . DB_PREFIX . 'tag t ON tg.tag_id = t.id
            WHERE g.id = :id
            GROUP BY g.id;');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $gameTab = $stmt->fetch();
        if ($gameTab === false) {
            return null;
        }
        $gameTab['tags'] = explode(',', $gameTab['tags']);
        return $this->hydrate($gameTab);
    }
}