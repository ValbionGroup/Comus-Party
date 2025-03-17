<?php
/**
 * @brief Fichier de déclaration et définition de la classe PasswordResetToken
 *
 * @file passwordResetToken.class.php
 * @author Lucas ESPIET "espiet.l@valbion.com"
 * @version 1.0
 * @date 2024-12-04
 */

namespace ComusParty\Models;

use DateTime;

/**
 * @brief Classe PasswordResetToken
 * @details La classe PasswordResetToken permet de représenter un token de réinitialisation de mot de passe
 */
class PasswordResetToken
{
    /**
     * @brief Identifiant de l'utilisateur
     * @var int
     */
    private int $userId;

    /**
     * @brief Token de réinitialisation de mot de passe
     * @var string
     */
    private string $token;

    /**
     * @brief Date de création du token
     * @var DateTime
     */
    private DateTime $createdAt;

    /**
     * @brief Constructeur de la classe PasswordResetToken
     * @param int $userId Identifiant de l'utilisateur
     * @param string $token Token de réinitialisation de mot de passe
     * @param DateTime $createdAt Date de création du token
     */
    public function __construct(int $userId, string $token, DateTime $createdAt)
    {
        $this->userId = $userId;
        $this->token = $token;
        $this->createdAt = $createdAt;
    }

    /**
     * @brief Retourne l'identifiant de l'utilisateur
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @brief Modifie l'identifiant de l'utilisateur
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @brief Retourne le token de réinitialisation de mot de passe
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @brief Modifie le token de réinitialisation de mot de passe
     * @param string $token
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    /**
     * @brief Retourne la date de création du token
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @brief Modifie la date de création du token
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}