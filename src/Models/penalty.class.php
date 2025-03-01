<?php

/**
 * @file penalty.class.php
 * @author Conchez-Boueytou Robin
 * @brief Le fichier contient la déclaration & définition de la classe Penalty.
 * @details
 * @date 01/03/2025
 * @version 0.1
 */

namespace ComusParty\Models;


use DateTime;


/**
 * @brief Classe Penalty
 * @description La classe Penalty permet de représenter les sanctions appliquées aux joueurs sur la plateforme
 */
class Penalty
{
    /**
     * @brief Identifiant de la sanction
     * @var int|null
     */
    private ?int $id;

    /**
     * @brief Identifiant du modérateur ayant créé la sanction
     * @var string|null
     */
    private ?string $createdBy;

    /**
     * @brief Identifiant du modérateur ayant annulé la sanction
     * @var string|null
     */
    private ?string $cancelledBy;

    /**
     * @brief Identifiant du joueur sanctionné
     * @var string|null
     */
    private ?string $penalizedUuid;

    /**
     * @brief Raison de la sanction
     * @var string|null
     */
    private ?string $reason;

    /**
     * @brief Durée de la sanction
     * @var int|null
     */
    private ?int $duration;

    /**
     * @brief Type de la sanction
     * @var string|null
     */
    private ?string $type;

    /**
     * @brief Date d'annulation de la sanction
     * @var DateTime|null
     */
    private ?DateTime $cancelledAt;

    /**
     * @brief Date de création de la sanction
     * @var DateTime|null
     */
    private ?DateTime $createdAt;

    /**
     * @brief Date de mise à jour de la sanction
     * @var DateTime|null
     */
    private ?DateTime $updatedAt;

    /**
     * @brief Constructeur de la classe Penalty
     * @param int|null $id Identifiant de la sanction
     * @param string|null $createdBy Identifiant du modérateur ayant créé la sanction
     * @param string|null $cancelledBy Identifiant du modérateur ayant annulé la sanction
     * @param string|null $penalizedUuid Identifiant du joueur sanctionné
     * @param string|null $reason Raison de la sanction
     * @param int|null $duration Durée de la sanction
     * @param string|null $type Type de la sanction
     * @param DateTime|null $cancelledAt Date d'annulation de la sanction
     * @param DateTime|null $createdAt Date de création de la sanction
     * @param DateTime|null $updatedAt Date de mise à jour de la sanction
     */
    public function __construct(?int $id = null
        , ?string                    $createdBy = null
        , ?string                    $cancelledBy = null
        , ?string                    $penalizedUuid = null
        , ?string                    $reason = null
        , ?int                       $duration = null
        , ?string                    $type = null
        , ?DateTime                  $cancelledAt = null
        , ?DateTime                  $createdAt = null
        , ?DateTime                  $updatedAt = null)
    {
        $this->id = $id;
        $this->createdBy = $createdBy;
        $this->cancelledBy = $cancelledBy;
        $this->penalizedUuid = $penalizedUuid;
        $this->reason = $reason;
        $this->duration = $duration;
        $this->type = $type;
        $this->cancelledAt = $cancelledAt;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    /**
     * @brief Retourne l'identifiant de la sanction
     * @return int|null L'identifiant de la sanction
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @brief Modifie l'identifiant de la sanction
     * @param int|null $id Le nouvel identifiant de la sanction
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @brief Retourne l'identifiant du modérateur ayant créé la sanction
     * @return string|null L'identifiant du modérateur ayant créé la sanction
     */
    public function getCreatedBy(): ?string
    {
        return $this->createdBy;
    }

    /**
     * @brief Modifie l'identifiant du modérateur ayant créé la sanction
     * @param string|null $createdBy Le nouvel identifiant du modérateur ayant créé la sanction
     */
    public function setCreatedBy(?string $createdBy): void
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @brief Retourne l'identifiant du modérateur ayant annulé la sanction
     * @return string|null L'identifiant du modérateur ayant annulé la sanction
     */
    public function getCancelledBy(): ?string
    {
        return $this->cancelledBy;
    }

    /**
     * @brief Modifie l'identifiant du modérateur ayant annulé la sanction
     * @param string|null $cancelledBy Le nouvel identifiant du modérateur ayant annulé la sanction
     */
    public function setCancelledBy(?string $cancelledBy): void
    {
        $this->cancelledBy = $cancelledBy;
    }

    /**
     * @brief Retourne l'identifiant du joueur sanctionné
     * @return string|null L'identifiant du joueur sanctionné
     */
    public function getPenalizedUuid(): ?string
    {
        return $this->penalizedUuid;
    }

    /**
     * @brief Modifie l'identifiant du joueur sanctionné
     * @param string|null $penalizedUuid Le nouvel identifiant du joueur sanctionné
     */
    public function setPenalizedUuid(?string $penalizedUuid): void
    {
        $this->penalizedUuid = $penalizedUuid;
    }

    /**
     * @brief Retourne la raison de la sanction
     * @return string|null La raison de la sanction
     */
    public function getReason(): ?string
    {
        return $this->reason;
    }

    /**
     * @brief Modifie la raison de la sanction
     * @param string|null $reason La nouvelle raison de la sanction
     */
    public function setReason(?string $reason): void
    {
        $this->reason = $reason;
    }

    /**
     * @brief Retourne la durée de la sanction
     * @return int|null La durée de la sanction
     */
    public function getDuration(): ?int
    {
        return $this->duration;
    }

    /**
     * @brief Modifie la durée de la sanction
     * @param int|null $duration La nouvelle durée de la sanction
     */
    public function setDuration(?int $duration): void
    {
        $this->duration = $duration;
    }

    /**
     * @brief Retourne le type de la sanction
     * @return string|null Le type de la sanction
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @brief Modifie le type de la sanction
     * @param string|null $type Le nouveau type de la sanction
     */
    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    /**
     * @brief Retourne la date d'annulation de la sanction
     * @return DateTime|null La date d'annulation de la sanction
     */
    public function getCancelledAt(): ?DateTime
    {
        return $this->cancelledAt;
    }

    /**
     * @brief Modifie la date d'annulation de la sanction
     * @param DateTime|null $cancelledAt La nouvelle date d'annulation de la sanction
     */
    public function setCancelledAt(?DateTime $cancelledAt): void
    {
        $this->cancelledAt = $cancelledAt;
    }

    /**
     * @brief Retourne la date de création de la sanction
     * @return DateTime|null La date de création de la sanction
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * @brief Modifie la date de création de la sanction
     * @param DateTime|null $createdAt La nouvelle date de création de la sanction
     */
    public function setCreatedAt(?DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @brief Retourne la date de mise à jour de la sanction
     * @return DateTime|null La date de mise à jour de la sanction
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @brief Modifie la date de mise à jour de la sanction
     * @param DateTime|null $updatedAt La nouvelle date de mise à jour de la sanction
     */
    public function setUpdatedAt(?DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

}