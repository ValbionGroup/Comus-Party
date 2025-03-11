<?php
/**
 * @file    report.class.php
 * @author  Estéban DESESSARD
 * @brief   Le fichier contient la déclaration & définition de la classe Report.
 * @date    23/01/2025
 * @version 0.1
 */

namespace ComusParty\Models;

use DateTime;

/**
 * @brief Les 4 thèmes pour les signalements
 * @enum ReportObject
 */
enum ReportObject
{
    /**
     * @brief Langage / propos incorrect
     */
    case LANGUAGE;

    /**
     * @brief Spam dans le chat
     */
    case SPAM;

    /**
     * @brief Publicité ou lien de partage
     */
    case LINKS;

    /**
     * @brief Anti-jeu ou manque de fairplay
     */
    case FAIRPLAY;

    /**
     * @biref Autres
     */
    case OTHER;
}


/**
 * @brief Classe Report
 * @description La classe report permet de représenter les signalements effectués par les joueurs sur la plateforme
 */
class Report
{
    /**
     * @brief Identifiant du signalement
     * @var int|null
     */
    private ?int $id;

    /**
     * @brief Objet du signalement
     * @var ReportObject|null
     */
    private ?ReportObject $object;

    /**
     * @brief Description du signalement
     * @var string|null
     */
    private ?string $description;

    /**
     * @brief UUID du modérateur ayant traité le signalement (null si pas encore traité)
     * @var string|null
     */
    private ?string $treatedBy;

    /**
     * @brief UUID du joueur signalé dans le signalement
     * @var string|null
     */
    private ?string $reportedUuid;

    /**
     * @brief UUID du joueur ayant effectué le signalement
     * @var string|null
     */
    private ?string $senderUuid;

    /**
     * @brief Date de création du signalement
     * @var DateTime|null
     */
    private ?DateTime $createdAt;

    /**
     * @brief Date de mise à jour du signalement
     * @var DateTime|null
     */
    private ?DateTime $updatedAt;

    /**
     * @param int|null $id Identifiant du signalement
     * @param ReportObject|null $object Objet du signalement
     * @param string|null $description Description du signalement
     * @param string|null $treatedBy UUID du modérateur ayant traité le signalement
     * @param string|null $reportedUuid UUID du joueur signalé
     * @param string|null $senderUuid UUID du joueur ayant rédigé le signalement
     * @param DateTime|null $createdAt Date de création du signalement
     * @param DateTime|null $updatedAt Date de dernière modification du signalement
     */
    public function __construct(
        ?int          $id = null,
        ?ReportObject $object = null,
        ?string       $description = null,
        ?string       $treatedBy = null,
        ?string       $reportedUuid = null,
        ?string       $senderUuid = null,
        ?DateTime     $createdAt = null,
        ?DateTime     $updatedAt = null)
    {
        $this->id = $id;
        $this->object = $object;
        $this->description = $description;
        $this->treatedBy = $treatedBy;
        $this->reportedUuid = $reportedUuid;
        $this->senderUuid = $senderUuid;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    /**
     * @brief Retourne l'identifiant du signalement
     * @return int|null L'identifiant du signalement
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @brief Définit l'identifiant du signalement
     * @param int|null $id Le nouvel identifiant du signalement
     * @return void
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @brief Retourne l'objet du signalement
     * @return ReportObject|null L'objet du signalement
     */
    public function getObject(): ?ReportObject
    {
        return $this->object;
    }

    /**
     * @brief Définit l'objet du signalement
     * @param ReportObject|null $object Le nouvel objet du signalement
     * @return void
     */
    public function setObject(?ReportObject $object): void
    {
        $this->object = $object;
    }

    /**
     * @brief Retourne la description du signalement
     * @return string|null La description du signalement
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @brief Définit la description du signalement
     * @param string|null $description La nouvelle description du signalement
     * @return void
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @brief Retourne l'UUID du modérateur ayant traité le signalement
     * @return string|null L'UUID du modérateur ayant traité le signalement
     */
    public function getTreatedBy(): ?string
    {
        return $this->treatedBy;
    }

    /**
     * @brief Définit l'UUID du modérateur ayant traité le signalement
     * @param string|null $treatedBy Le nouvel UUID du modérateur ayant traité le signalement
     * @return void
     */
    public function setTreatedBy(?string $treatedBy): void
    {
        $this->treatedBy = $treatedBy;
    }

    /**
     * @brief Retourne l'UUID du joueur signalé dans le signalement
     * @return string|null L'UUID du joueur signalé dans le signalement
     */
    public function getReportedUuid(): ?string
    {
        return $this->reportedUuid;
    }

    /**
     * @brief Définit l'UUID du joueur signalé dans le signalement
     * @param string|null $reportedUuid Le nouvel UUID du joueur signalé dans le signalement
     * @return void
     */
    public function setReportedUuid(?string $reportedUuid): void
    {
        $this->reportedUuid = $reportedUuid;
    }

    /**
     * @brief Retourne l'UUID du joueur ayant effectué le signalement
     * @return string|null L'UUID du joueur ayant effectué le signalement
     */
    public function getSenderUuid(): ?string
    {
        return $this->senderUuid;
    }

    /**
     * @brief Définit l'UUID du joueur ayant effectué le signalement
     * @param string|null $senderUuid Le nouvel UUID du joueur ayant effectué le signalement
     * @return void
     */
    public function setSenderUuid(?string $senderUuid): void
    {
        $this->senderUuid = $senderUuid;
    }

    /**
     * @brief Retourne la date de création du signalement
     * @return DateTime|null La date de création du signalement
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * @brief Définit la date de création du signalement
     * @param DateTime|null $createdAt La nouvelle date de création du signalement
     * @return void
     */
    public function setCreatedAt(?DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @brief Retourne la date de mise à jour du signalement
     * @return DateTime|null La date de mise à jour du signalement
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @brief Définit la date de mise à jour du signalement
     * @param DateTime|null $updatedAt La nouvelle date de mise à jour du signalement
     * @return void
     */
    public function setUpdatedAt(?DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}