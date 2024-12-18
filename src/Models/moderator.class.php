<?php

/**
 * @file moderator.class.php
 * @author Conchez-Boueytou Robin
 * @brief Le fichier contient la déclaration & définition de la classe Moderator.
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
     * @var string|null
     */
    private ?string $uuid;
    /**
     * @brief L'identifiant de l'utilisateur associé au modérateur
     * @var int|null
     */
    private ?int $userId;
    /**
     * @brief Le prénom du modérateur
     * @var string|null
     */
    private ?string $firstName;
    /**
     * @brief Le nom de famille du modérateur
     * @var string|null
     */
    private ?string $lastName;
    /**
     * @brief La date de création du profil de modérateur
     * @var DateTime|null
     */
    private ?DateTime $createdAt;
    /**
     * @brief La date de mise à jour du profil de modérateur
     * @var DateTime|null
     */
    private ?DateTime $updatedAt;

    /**
     * @brief Constructeur de la classe Moderator
     * @param string|null $uuid
     * @param int|null $userId
     * @param string|null $firstName
     * @param string|null $lastName
     * @param DateTime|null $createdAt
     * @param DateTime|null $updatedAt
     */
    public function __construct(?string $uuid = null,
                                ?int $userId = null,
                                ?string $firstName = null,
                                ?string $lastName = null,
                                ?DateTime $createdAt = null,
                                ?DateTime $updatedAt = null
    ){
        $this->uuid = $uuid;
        $this->userId = $userId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    /**
     * @brief Retourne l'UUID du modérateur
     * @return string|null
     */
    public function getUuid(): ?string
    {
        return $this->uuid;
    }
    /**
     * @brief Retourne l'identifiant de l'utilisateur associé au modérateur
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }
    /**
     * @brief Retourne le prénom du modérateur
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }
    /**
     * @brief Retourne le nom de famille du modérateur
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }
    /**
     * @brief Retourne la date de création du profil de modérateur
     * @return DateTime|null
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }
    /**
     * @brief Retourne la date de mise à jour du profil de modérateur
     * @return DateTime|null
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
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
     * @brief Modifie l'identifiant de l'utilisateur associé au modérateur
     * @param int|null $userId Le nouvel identifiant de l'utilisateur associé au modérateur
     * @return void
     */
    public function setUserId(?int $userId): void
    {
        $this->userId = $userId;
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
     * @brief Modifie le nom de famille du modérateur
     * @param string|null $lastName Le nouveau nom de famille du modérateur
     * @return void
     */
    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
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
     * @brief Modifie la date de mise à jour du profil de modérateur
     * @param DateTime|null $updatedAt
     * @return void
     */
    public function setUpdatedAt(?DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }



}