<?php
/**
 * @brief Classe GameRecordDao
 *
 * @file gamerecord.dao.class.php
 * @author Lucas ESPIET "espiet.l@valbion.com"
 * @version 1.0
 * @date 2024-12-18
 */

namespace ComusParty\Models;

use DateTime;
use Exception;
use PDO;

/**
 * @brief Classe GameRecordDAO
 * @details La classe GameRecordDAO permet de gérer les parties en base de données
 */
class GameRecordDAO
{
    /**
     * @brief Connexion à la base de données
     * @var PDO|null Connexion à la base de données
     */
    private ?PDO $pdo;

    /**
     * @brief Constructeur de la classe GameRecordDAO
     * @param PDO|null $pdo Connexion à la base de données
     */
    public function __construct(?PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @brief Retourne la liste des parties grâce à l'ID du jeu
     * @param int $gameId ID de la partie
     * @return GameRecord[]|null Tableau d'objets GameRecord (ou null si une erreur survient)
     * @throws Exception
     */
    public function findByGameId(int $gameId): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM " . DB_PREFIX . "game_record WHERE game_id = :gameId");
        $stmt->bindParam(":gameId", $gameId);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $gameRecords = $stmt->fetchAll();
        if (!$gameRecords) {
            return null;
        }
        return $this->hydrateMany($gameRecords);
    }

    /**
     * @brief Retourne la liste des parties hydratées
     * @param array $rows Tableau de lignes de la table game_record
     * @return GameRecord[] Tableau d'objets GameRecord
     * @throws Exception
     */
    private function hydrateMany(array $rows): array
    {
        $gameRecords = [];
        foreach ($rows as $row) {
            $gameRecords[] = $this->hydrate($row);
        }
        return $gameRecords;
    }

    /**
     * @brief Retourne un enregistrement de partie hydraté
     * @param array $row Ligne de la table game_record
     * @return GameRecord Enregistrement de la partie hydraté
     * @throws Exception Exception levée en cas d'erreur lors de l'hydratation
     */
    private function hydrate(array $row): GameRecord
    {
        $hostedBy = (new PlayerDAO($this->getPdo()))->findByUuid($row["hosted_by"]);
        $game = (new GameDAO($this->getPdo()))->findById($row["game_id"]);
        $gameRecordState = match ($row["state"]) {
            "waiting" => GameRecordState::WAITING,
            "started" => GameRecordState::STARTED,
            "finished" => GameRecordState::FINISHED,
        };
        $finishedAt = $row["finished_at"] ? new DateTime($row["finished_at"]) : null;
        $players = $this->findPlayersByGameRecordUuid($row["uuid"]);
        if (!is_null($players)) {
            $players = array_map(fn($player) => (new PlayerDAO($this->getPdo()))->findByUuid($player["player_uuid"]), $players);
        }

        return new GameRecord(
            $row["uuid"],
            $game,
            $hostedBy,
            $players,
            $gameRecordState,
            new DateTime($row["created_at"]),
            new DateTime($row["updated_at"]),
            $finishedAt
        );
    }

    /**
     * @brief Retourne la liste des joueurs dans une partie grâce à l'UUID de celle-ci
     * @param string $uuid UUID de la partie
     * @return array|null
     */
    private function findPlayersByGameRecordUuid(string $uuid): ?array
    {
        $stmt = $this->pdo->prepare("SELECT player_uuid FROM " . DB_PREFIX . "played WHERE game_uuid = :uuid");
        $stmt->bindParam(":uuid", $uuid);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $players = $stmt->fetchAll();
        if (!$players) {
            return null;
        }
        return $players;
    }

    /**
     * @brief Retourne un objet GameRecord (ou null) à partir de l'UUID passé en paramètre
     *
     * @param string $uuid L'UUID de la partie recherchée
     * @return GameRecord|null Enregistrement de la partie (GameRecord) (ou null si non-trouvé)
     * @throws Exception
     */
    public function findByUuid(string $uuid): ?GameRecord
    {
        $stmt = $this->pdo->prepare("SELECT * FROM " . DB_PREFIX . "game_record WHERE uuid = :uuid");
        $stmt->bindParam(":uuid", $uuid);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $gameRecord = $stmt->fetch();
        if (!$gameRecord) {
            return null;
        }
        return $this->hydrate($gameRecord);
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
     * @brief Retourne un tableau d'objets GameRecord (ou null) à partir de l'UUID passé en paramètre correspondant aux parties hébergées par un joueur
     *
     * @param string $uuid L'UUID du joueur ayant hébergé les parties
     * @return GameRecord[]|null Tableau de GameRecord (ou null si non-trouvé)
     * @throws Exception
     */
    public function findByHosterUuid(string $uuid): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM " . DB_PREFIX . "game_record WHERE hosted_by = :uuid");
        $stmt->bindParam(":uuid", $uuid);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $gameRecords = $stmt->fetchAll();
        if (!$gameRecords) {
            return null;
        }
        return $this->hydrateMany($gameRecords);
    }


    /**
     * @brief Retourne un tableau d'objets GameRecord (ou null) à partir de l'état passé en paramètre
     *
     * @param GameRecordState $state L'état des parties recherchées
     * @return GameRecord[]|null Tableau de GameRecord (ou null si non-trouvé)
     * @throws Exception
     */
    public function findByState(GameRecordState $state): ?array
    {
        $state = match ($state) {
            GameRecordState::WAITING => "waiting",
            GameRecordState::STARTED => "started",
            GameRecordState::FINISHED => "finished",
        };

        $stmt = $this->pdo->prepare("SELECT * FROM " . DB_PREFIX . "game_record WHERE state = :state");
        $stmt->bindParam(":state", $state);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $gameRecords = $stmt->fetchAll();
        if (!$gameRecords) {
            return null;
        }
        return $this->hydrateMany($gameRecords);
    }
}