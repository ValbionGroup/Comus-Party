<?php
/**
 * @brief Classe rememberToken
 *
 * @file rememberToken.class.php
 * @author Lucas ESPIET "espiet.l@valbion.com"
 * @version 1.0
 * @date 2025-02-26
 */

namespace ComusParty\Models;

use DateTime;
use Random\RandomException;

/**
 * @brief Classe rememberToken
 * @details La classe rememberToken permet de gérer les jetons de connexion
 */
class RememberToken
{
    /**
     * @var int $userId Identifiant de l'utilisateur associé au jeton de connexion
     * @brief Identifiant de l'utilisateur associé au jeton de connexion
     */
    private int $userId;

    /**
     * @var string $token Jeton de connexion
     * @brief Jeton de connexion
     */
    private string $token;

    /**
     * @var DateTime $createdAt Date de création du jeton de connexion
     * @brief Date de création du jeton de connexion
     */
    private DateTime $createdAt;

    /**
     * @var DateTime $expiresAt Date d'expiration du jeton de connexion
     * @brief Date d'expiration du jeton de connexion
     */
    private DateTime $expiresAt;


    /**
     * @brief Constructeur de la classe rememberToken
     * @param int $userId Identifiant de l'utilisateur associé au jeton de connexion
     * @param string $token Jeton de connexion
     * @param DateTime|null $createdAt Date de création du jeton de connexion
     * @param DateTime|null $expiresAt Date d'expiration du jeton de connexion
     */
    public function __construct(int $userId, string $token, ?DateTime $createdAt = null, ?DateTime $expiresAt = null)
    {
        $this->userId = $userId;
        $this->token = $token;
        $this->createdAt = $createdAt ?? new DateTime();
        $this->expiresAt = $expiresAt ?? new DateTime('+1 month');
    }

    /**
     * @brief Retourne l'identifiant de l'utilisateur associé au jeton de connexion
     * @return int Identifiant de l'utilisateur associé au jeton de connexion
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @brief Modifie l'identifiant de l'utilisateur associé au jeton de connexion
     * @param int $userId Nouvel identifiant de l'utilisateur associé au jeton de connexion
     * @return void
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @brief Retourne le jeton de connexion
     * @return string Jeton de connexion
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @brief Modifie le jeton de connexion
     * @param string $token Nouveau jeton de connexion
     * @return void
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    /**
     * @brief Retourne la date de création du jeton de connexion
     * @return DateTime Date de création du jeton de connexion
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @brief Modifie la date de création du jeton de connexion
     * @param DateTime $createdAt Nouvelle date de création du jeton de connexion
     * @return void
     */
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @brief Retourne la date d'expiration du jeton de connexion
     * @return DateTime Date d'expiration du jeton de connexion
     */
    public function getExpiresAt(): DateTime
    {
        return $this->expiresAt;
    }

    /**
     * @brief Modifie la date d'expiration du jeton de connexion
     * @param DateTime $expiresAt Nouvelle date d'expiration du jeton de connexion
     * @return void
     */
    public function setExpiresAt(DateTime $expiresAt): void
    {
        $this->expiresAt = $expiresAt;
    }

    /**
     * @brief Retourne un tableau associatif contenant les informations du jeton de connexion
     * @return array Tableau associatif contenant les informations du jeton de connexion
     */
    public function __toArray(): array
    {
        return [
            'userId' => $this->userId,
            'token' => $this->token,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'expiresAt' => $this->expiresAt->format('Y-m-d H:i:s')
        ];
    }

    /**
     * @brief Vérifie si le jeton de connexion est valide
     * @return bool True si le jeton de connexion est valide, false sinon
     */
    public function isValid(): bool
    {
        return $this->expiresAt > new DateTime();
    }

    /**
     * @brief Génère un jeton de connexion
     * @return string Jeton de connexion généré
     * @throws RandomException Exception levée si la génération du jeton de connexion a échoué
     */
    public function generateToken(): string
    {
        $this->token = bin2hex(random_bytes(32));
        return $this->token;
    }
}