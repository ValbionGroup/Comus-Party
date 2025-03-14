<?php

/**
 * @file moderator.class.php
 * @author Conchez-Boueytou Robin
 * @brief Fichier de déclaration et définition de la classe Moderator
 * @date 18/12/2024
 * @version 0.1
 */

namespace ComusParty\Models;

use DateTime;

/**
 * @brief Classe Moderator
 * @details La classe Moderator représente un modérateur de l'application
 */
class Moderator
{
    /**
     * @brief L'UUID du modérateur, identifiant unique
     * @var string|null UUID
     */
    private ?string $uuid;

    /**
     * @brief L'identifiant de l'utilisateur associé au modérateur
     * @var int|null Identifiant de l'utilisateur lié
     */
    private ?int $userId;

    /**
     * @brief Le prénom du modérateur
     * @var string|null Prénom
     */
    private ?string $firstName;

    /**
     * @brief Le nom de famille du modérateur
     * @var string|null Nom de famille
     */
    private ?string $lastName;

    /**
     * @brief La date de création du profil de modérateur
     * @var DateTime|null Date de création
     */
    private ?DateTime $createdAt;

    /**
     * @brief La date de mise à jour du profil de modérateur
     * @var DateTime|null Date de mise à jour
     */
    private ?DateTime $updatedAt;

    /**
     * @brief Constructeur de la classe Moderator
     * @param string|null $uuid L'UUID du modérateur
     * @param int|null $userId L'identifiant de l'utilisateur associé au modérateur
     * @param string|null $firstName Le prénom du modérateur
     * @param string|null $lastName Le nom de famille du modérateur
     * @param DateTime|null $createdAt La date de création du profil de modérateur
     * @param DateTime|null $updatedAt La date de mise à jour du profil de modérateur
     */
    public function __construct(?string   $uuid = null,
                                ?int      $userId = null,
                                ?string   $firstName = null,
                                ?string   $lastName = null,
                                ?DateTime $createdAt = null,
                                ?DateTime $updatedAt = null
    )
    {
        $this->uuid = $uuid;
        $this->userId = $userId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    /**
     * @brief Retourne l'UUID du modérateur
     * @return string|null L'UUID du modérateur
     */
    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    /**
     * @brief Modifie l'UUID du modérateur
     * @param string|null $uuid Le nouvel UUID du modérateur
     * @return void
     */
    public function setUuid(?string $uuid): void
    {
        $this->uuid = $uuid;
    }

    /**
     * @brief Retourne l'identifiant de l'utilisateur associé au modérateur
     * @return int|null L'identifiant de l'utilisateur associé au modérateur
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * @brief Modifie l'identifiant de l'utilisateur associé au modérateur
     * @param int|null $userId Le nouvel identifiant de l'utilisateur associé au modérateur
     * @return void
     */
    public function setUserId(?int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @brief Retourne le prénom du modérateur
     * @return string|null Le prénom du modérateur
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @brief Modifie le prénom du modérateur
     * @param string|null $firstName Le nouveau prénom du modérateur
     * @return void
     */
    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @brief Retourne le nom de famille du modérateur
     * @return string|null Le nom de famille du modérateur
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @brief Modifie le nom de famille du modérateur
     * @param string|null $lastName Le nouveau nom de famille du modérateur
     * @return void
     */
    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @brief Retourne la date de création du profil de modérateur
     * @return DateTime|null La date de création du profil de modérateur
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * @brief Modifie la date de création du profil de modérateur
     * @param DateTime|null $createdAt La nouvelle date de création du profil de modérateur
     * @return void
     */
    public function setCreatedAt(?DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @brief Retourne la date de mise à jour du profil de modérateur
     * @return DateTime|null La date de mise à jour du profil de modérateur
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @brief Modifie la date de mise à jour du profil de modérateur
     * @param DateTime|null $updatedAt
     * @return void
     */
    public function setUpdatedAt(?DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }


}