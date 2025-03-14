<?php

/**
 * @file penalty.dao.php
 * @author Conchez-Boueytou Robin
 * @brief Fichier de déclaration et définition de la classe PenaltyDAO
 * @details La classe PenaltyDAO permet de gérer les actions liées aux sanctions dans la base de données
 * @date 01/03/2025
 * @version 0.0
 */

namespace ComusParty\Models;


use DateMalformedStringException;
use DateTime;
use PDO;

/**
 * @brief Classe PenaltyDAO
 * @details La classe PenaltyDAO permet de gérer les actions liées aux sanctions dans la base de données
 */
class PenaltyDAO
{
    /**
     * @brief La connexion à la base de données
     * @var PDO|null
     */
    private ?PDO $pdo;

    /**
     * @brief Le constructeur de la classe PenaltyDAO
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
     * @brief Hydrate un tableau de données en objets Penalty
     * @return array Un tableau contenant toutes les sanctions
     * @throws DateMalformedStringException Exception lancée si la date est mal formée
     */
    public function hydrateMany(array $datas): array
    {
        $penalties = [];
        foreach ($datas as $data) {
            $penalties[] = $this->hydrate($data);
        }
        return $penalties;
    }

    /**
     * @brief Hydrate un tableau de données en objet Penalty
     * @param array $data Le tableau de données à hydrater
     * @return Penalty L'objet Penalty hydraté
     * @throws DateMalformedStringException Exception lancée si la date est mal formée
     */
    public function hydrate(array $data): Penalty
    {
        $penalty = new Penalty();
        $penalty->setId($data['id']);
        $penalty->setCreatedBy($data['created_by']);
        $penalty->setCancelledBy($data['cancelled_by']);
        $penalty->setPenalizedUuid($data['penalized_uuid']);
        $penalty->setReason($data['reason']);
        $penalty->setDuration($data['duration']);
        $penalty->setCreatedAt(new DateTime($data['created_at']));
        $penalty->setUpdatedAt(new DateTime($data['updated_at']));
        return $penalty;
    }

    /**
     * @brief Créer une sanction en base de données
     * @param Penalty $penalty La sanction à créer
     * @return bool Retourne true si la création a réussi, false sinon
     */
    public function createPenalty(Penalty $penalty): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO " . DB_PREFIX . "penalty (created_by, penalized_uuid, reason, duration, type, created_at, updated_at) VALUES (:created_by, :penalized_uuid, :reason, :duration, :type, :created_at, :updated_at)");

        $createdBy = $penalty->getCreatedBy();
        $penalizedUuid = $penalty->getPenalizedUuid();
        $reason = $penalty->getReason();
        $duration = $penalty->getDuration();
        $penaltyType = $this->transformPenaltyTypeToString($penalty->getType());
        $createdAt = $penalty->getCreatedAt()->format('Y-m-d H:i:s');
        $updatedAt = $penalty->getUpdatedAt()->format('Y-m-d H:i:s');

        $stmt->bindParam(":created_by", $createdBy);
        $stmt->bindParam(":penalized_uuid", $penalizedUuid);
        $stmt->bindParam(":reason", $reason);
        $stmt->bindParam(":duration", $duration);
        $stmt->bindParam(":type", $penaltyType);
        $stmt->bindParam(":created_at", $createdAt);
        $stmt->bindParam(":updated_at", $updatedAt);

        return $stmt->execute();
    }

    /**
     * @brief Transforme un type de sanction en string
     * @param PenaltyType|null $penaltyType Le type de sanction à transformer
     * @return string|null Le type de sanction transformé en string
     */
    private function transformPenaltyTypeToString(?PenaltyType $penaltyType): ?string
    {
        return match ($penaltyType) {
            PenaltyType::MUTED => 'muted',
            PenaltyType::BANNED => 'banned',
            default => null
        };
    }

    /**
     * @brief Trouve la dernière sanction d'un joueur de type muted
     * @param string $playerUuid L'UUID du joueur
     * @return Penalty|null La dernière sanction de type muted
     * @throws DateMalformedStringException Exception lancée si la date est mal formée
     */
    public function findLastMutedByPlayerUuid(string $playerUuid): ?Penalty
    {
        $stmt = $this->pdo->prepare("SELECT * FROM " . DB_PREFIX . "penalty WHERE penalized_uuid = :penalized_uuid AND type = 'muted' ORDER BY created_at DESC LIMIT 1");
        $stmt->bindParam(":penalized_uuid", $playerUuid);
        $stmt->execute();
        $data = $stmt->fetch();
        if ($data === false) {
            return null;
        }
        return $this->hydrate($data);
    }

    /**
     * @brief Trouve la dernière sanction d'un joueur
     * @param string $playerUuid L'UUID du joueur
     * @return Penalty|null La dernière sanction
     * @throws DateMalformedStringException Exception lancée si la date est mal formée
     */
    public function findLastPenaltyByPlayerUuid(string $playerUuid): ?Penalty
    {
        $stmt = $this->pdo->prepare("SELECT * FROM " . DB_PREFIX . "penalty WHERE penalized_uuid = :penalized_uuid ORDER BY created_at DESC LIMIT 1");
        $stmt->bindParam(":penalized_uuid", $playerUuid);
        $stmt->execute();
        $data = $stmt->fetch();
        if ($data === false) {
            return null;
        }
        return $this->hydrate($data);
    }
}