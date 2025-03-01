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

enum PenaltyType
{
    case MUTE;
    case BANNED;
}

class Penalty
{
    private ?int $id;

    private ?string $createdBy;

    private ?string $cancelledBy;

    private ?string $penalizedUuid;

    private ?string $reason;

    private ?int $duration;

    private ?PenaltyType $type;

    private ?DateTime $cancelledAt;

    private ?DateTime $createdAt;

    private ?DateTime $updatedAt;

    public function __construct(?int $id = null
        , ?string                    $createdBy = null
        , ?string                    $cancelledBy = null
        , ?string                    $penalizedUuid = null
        , ?string                    $reason = null
        , ?int                       $duration = null
        , ?PenaltyType               $type = null
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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getCreatedBy(): ?string
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?string $createdBy): void
    {
        $this->createdBy = $createdBy;
    }

    public function getCancelledBy(): ?string
    {
        return $this->cancelledBy;
    }

    public function setCancelledBy(?string $cancelledBy): void
    {
        $this->cancelledBy = $cancelledBy;
    }

    public function getPenalizedUuid(): ?string
    {
        return $this->penalizedUuid;
    }

    public function setPenalizedUuid(?string $penalizedUuid): void
    {
        $this->penalizedUuid = $penalizedUuid;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(?string $reason): void
    {
        $this->reason = $reason;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): void
    {
        $this->duration = $duration;
    }

    public function getType(): ?PenaltyType
    {
        return $this->type;
    }

    public function setType(?PenaltyType $type): void
    {
        $this->type = $type;
    }

    public function getCancelledAt(): ?DateTime
    {
        return $this->cancelledAt;
    }

    public function setCancelledAt(?DateTime $cancelledAt): void
    {
        $this->cancelledAt = $cancelledAt;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

}