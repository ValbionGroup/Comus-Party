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
     * @brief Constructeur de la classe UserDAO
     * @param PDO|null $pdo Connexion à la base de données
     */
    public function __construct(?PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @brief Retourne la liste des parties grâce à l'ID du jeu
     * @param int $gameId ID de la partie
     * @return GameRecord[] Tableau d'objets GameRecord
     * @throws Exception
     */
    public function findByGameId(int $gameId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM " . DB_PREFIX . "game_record WHERE game_id = :gameId");
        $stmt->execute([
            "gameId" => $gameId
        ]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        return $this->hydrateMany($stmt->fetch());
    }

    /**
     * @brief Retourne la liste des parties
     * @param array $rows Tableau de lignes de la table game_record
     * @return GameRecord[] Tableau d'objets GameRecord
     * @throws Exception
     */
    public function hydrateMany(array $rows): array
    {
        $gameRecords = [];
        foreach ($rows as $row) {
            $gameRecords[] = $this->hydrate($row);
        }
        return $gameRecords;
    }

    /**
     * @brief Retourne la liste des parties
     * @param array $row Ligne de la table game_record
     * @return GameRecord Enregistrement de la partie hydraté
     * @throws Exception
     */
    public function hydrate(array $row): GameRecord
    {
        $hostedBy = (new PlayerDAO($this->getPdo()))->findByUuid($row["hostedBy"]);
        $game = (new GameDAO($this->getPdo()))->findById($row["game"]);
        $gameRecordState = match ($row["state"]) {
            "waiting" => GameRecordState::WAITING,
            "started" => GameRecordState::STARTED,
            "finished" => GameRecordState::FINISHED,
        };
        $finishedAt = $row["finishedAt"] ? new DateTime($row["finishedAt"]) : null;
        $players = null;
        if ($row['player'] !== null) {
            for ($i = 0; $i < count($row['player']); $i++) {
                $players[] = (new PlayerDAO($this->getPdo()))->findByUuid($row['player'][$i]);
            }
        }

        return new GameRecord(
            $row["uuid"],
            $game,
            $hostedBy,
            $players,
            $gameRecordState,
            new DateTime($row["createdAt"]),
            new DateTime($row["startedAt"]),
            $finishedAt
        );
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
        $stmt->execute([
            "uuid" => $uuid
        ]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        return $this->hydrate($stmt->fetch());
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
     * @brief Retourne un tableau d'objets GameRecord (ou null) à partir de l'UUID passé en paramètre
     *
     * @param string $uuid L'UUID de la partie recherchée
     * @return GameRecord[]|null Tableau de GameRecord (ou null si non-trouvé)
     * @throws Exception
     */
    public function findByHosterUuid(string $uuid): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM " . DB_PREFIX . "game_record WHERE hosted_by = :uuid");
        $stmt->execute([
            "uuid" => $uuid
        ]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        return $this->hydrateMany($stmt->fetch());
    }


    /**
     * @brief Retourne un objet GameRecord (ou null) à partir de l'ID passé en paramètre
     *
     * @param GameRecordState $state L'état de la partie recherchée
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
        $stmt->execute([
            "state" => $state
        ]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        return $this->hydrateMany($stmt->fetch());
    }
}