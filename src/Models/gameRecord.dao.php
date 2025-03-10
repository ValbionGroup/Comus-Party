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
            default => GameRecordState::UNKNOWN,
        };
        $finishedAt = $row["finished_at"] ? new DateTime($row["finished_at"]) : null;
        $players = $this->findPlayersByGameRecordCode($row["code"]);
        if (!is_null($players)) {
            $players = array_map(function ($player) {
                $playerObject = (new PlayerDAO($this->getPdo()))->findByUuid($player["player_uuid"]);
                return [
                    "player" => $playerObject,
                    "token" => $player["token"]
                ];
            }, $players);
        }

        return new GameRecord(
            $row["code"],
            $row["token"],
            $game,
            $hostedBy,
            $players,
            $gameRecordState,
            $row["private"] === 1,
            new DateTime($row["created_at"]),
            new DateTime($row["updated_at"]),
            $finishedAt
        );
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
     * @brief Retourne la liste des joueurs dans une partie grâce au code de celle-ci
     * @param string $code Code de la partie
     * @return array|null
     */
    private function findPlayersByGameRecordCode(string $code): ?array
    {
        $stmt = $this->pdo->prepare("SELECT player_uuid, token FROM " . DB_PREFIX . "played WHERE game_code = :code");
        $stmt->bindParam(":code", $code);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $players = $stmt->fetchAll();
        if (!$players) {
            return null;
        }
        return $players;
    }

    /**
     * @brief Retourne un objet GameRecord (ou null) à partir du code passé en paramètre
     *
     * @param string $code Code de la partie recherchée
     * @return GameRecord|null Enregistrement de la partie (GameRecord) (ou null si non-trouvé)
     * @throws Exception
     */
    public function findByCode(string $code): ?GameRecord
    {
        $stmt = $this->pdo->prepare("SELECT * FROM " . DB_PREFIX . "game_record WHERE code = :code");
        $stmt->bindParam(":code", $code);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $gameRecord = $stmt->fetch();
        if (!$gameRecord) {
            return null;
        }
        return $this->hydrate($gameRecord);
    }

    /**
     * @brief Retourne un tableau d'objets GameRecord (ou null) à partir de l'UUID passé en paramètre correspondant aux parties hébergées par un joueur
     *
     * @param string $uuid L'UUID du joueur ayant hébergé les parties
     * @return GameRecord[]|null Tableau de GameRecord (ou null si non-trouvé)
     * @throws Exception
     */
    public function findByHostUuid(string $uuid): ?array
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
            GameRecordState::UNKNOWN => null,
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

    /**
     * @brief Insère un enregistrement de partie en base de données
     * @param GameRecord $gameRecord Enregistrement de la partie à insérer
     * @return bool Retourne true si l'insertion a réussi, false sinon
     */
    public function insert(GameRecord $gameRecord): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO " . DB_PREFIX . "game_record (code, token, game_id, hosted_by, state, private, created_at, updated_at, finished_at) VALUES (:code, :token, :gameId, :hostedBy, :state, :isPrivate, :createdAt, :updatedAt, :finishedAt)");

        $code = $gameRecord->getCode();
        $token = $gameRecord->getToken();
        $gameId = $gameRecord->getGame()->getId();
        $hostUuid = $gameRecord->getHostedBy()->getUuid();
        $state = match ($gameRecord->getState()) {
            GameRecordState::WAITING => "waiting",
            GameRecordState::STARTED => "started",
            GameRecordState::FINISHED => "finished",
            GameRecordState::UNKNOWN => null,
        };
        $isPrivate = $gameRecord->isPrivate();
        $createdAt = $gameRecord->getCreatedAt()->format("Y-m-d H:i:s");
        $updatedAt = $gameRecord->getUpdatedAt()?->format("Y-m-d H:i:s");
        $finishedAt = $gameRecord->getFinishedAt()?->format("Y-m-d H:i:s");

        $stmt->bindParam(":code", $code);
        $stmt->bindParam(":token", $token);
        $stmt->bindParam(":gameId", $gameId);
        $stmt->bindParam(":hostedBy", $hostUuid);
        $stmt->bindParam(":state", $state);
        $stmt->bindParam(":isPrivate", $isPrivate, PDO::PARAM_BOOL);
        $stmt->bindParam(":createdAt", $createdAt);
        $stmt->bindParam(":updatedAt", $updatedAt);
        $stmt->bindParam(":finishedAt", $finishedAt);

        return $stmt->execute();
    }

    /**
     * @brief Met à jour un enregistrement de partie en base de données
     * @param GameRecord $gameRecord Enregistrement de la partie à mettre à jour
     * @return bool Retourne true si la mise à jour a réussi, false sinon
     */
    public function update(GameRecord $gameRecord): bool
    {
        $stmt = $this->pdo->prepare("UPDATE " . DB_PREFIX . "game_record SET token = :token, state = :state, private = :private, finished_at = :finishedAt WHERE code = :code");

        $token = $gameRecord->getToken();
        $state = match ($gameRecord->getState()) {
            GameRecordState::WAITING => "waiting",
            GameRecordState::STARTED => "started",
            GameRecordState::FINISHED => "finished",
            GameRecordState::UNKNOWN => null,
        };
        $private = $gameRecord->isPrivate();
        $finishedAt = $gameRecord->getFinishedAt()?->format("Y-m-d H:i:s");
        $code = $gameRecord->getCode();

        $stmt->bindParam(":token", $token);
        $stmt->bindParam(":state", $state);
        $stmt->bindParam(":private", $private, PDO::PARAM_BOOL);
        $stmt->bindParam(":finishedAt", $finishedAt);
        $stmt->bindParam(":code", $code);

        return $stmt->execute();
    }

    /**
     * @brief Met à jour les joueurs d'une partie en base de données
     * @param string $gameCode Code de la partie à modifier
     * @param array $players Tableau de joueurs à mettre à jour
     * @return bool Retourne true si la mise à jour a réussi, false sinon
     */
    public function updatePlayers(string $gameCode, array $players): bool
    {
        $stmt = $this->pdo->prepare("UPDATE " . DB_PREFIX . "played SET token = :token WHERE game_code = :gameCode AND player_uuid = :playerUuid");
        foreach ($players as $player) {
            $stmt->bindParam(":token", $player["token"]);
            $stmt->bindParam(":gameCode", $gameCode);
            $uuid = $player["player"]->getUuid();
            $stmt->bindParam(":playerUuid", $uuid);
            if (!$stmt->execute()) {
                return false;
            }
        }
        return true;
    }

    /**
     * @brief Supprime un enregistrement de partie en base de données
     * @param string $code Code de la partie à supprimer
     * @return bool Retourne true si la suppression a réussi, false sinon
     */
    public function delete(string $code): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM " . DB_PREFIX . "game_record WHERE code = :code");
        $stmt->bindParam(":code", $code);
        return $stmt->execute();
    }

    /**
     * @brief Ajoute un joueur à une partie en base de données
     * @param GameRecord $gameRecord Enregistrement de la partie
     * @param Player $player Joueur à ajouter
     * @return bool Retourne true si l'ajout a réussi, false sinon
     */
    public function addPlayer(GameRecord $gameRecord, Player $player): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO " . DB_PREFIX . "played (game_code, player_uuid) VALUES (:gameCode, :playerUuid)");
        $gameRecord->addPlayer($player);
        $playerUuid = $player->getUuid();
        $gameCode = $gameRecord->getCode();
        $stmt->bindParam(":gameCode", $gameCode);
        $stmt->bindParam(":playerUuid", $playerUuid);
        return $stmt->execute();
    }

    /**
     * @brief Supprime un joueur d'une partie
     * @param string $gameCode Code de la partie
     * @param string $playerUuid UUID du joueur à supprimer
     * @return bool Retourne true si la suppression a réussi, false sinon
     */
    public function removePlayer(string $gameCode, string $playerUuid): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM " . DB_PREFIX . "played WHERE game_code = :gameCode AND player_uuid = :playerUuid");
        $stmt->bindParam(":gameCode", $gameCode);
        $stmt->bindParam(":playerUuid", $playerUuid);
        return $stmt->execute();
    }

    /**
     * @brief Enregistre un gagnant d'une partie dans la table cp_won
     * @param string $code Code de la partie
     * @param mixed $uuid UUID du joueur gagnant
     * @return bool Retourne true si l'ajout a réussi, false sinon
     */
    public function addWinner(string $code, mixed $uuid): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO " . DB_PREFIX . "won (game_code, player_uuid) VALUES (:gameCode, :playerUuid)");
        $stmt->bindParam(":gameCode", $code);
        $stmt->bindParam(":playerUuid", $uuid);
        return $stmt->execute();
    }
}