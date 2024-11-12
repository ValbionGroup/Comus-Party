<?php

/**
 * Objet représentant un joueur
 */
class Player {
    /**
     * L'UUID du joueur (clé primaire)
     *
     * @var string
     */
    private ?string $uuid;

    /**
     * Nom d'utilisateur du joueur
     *
     * @var string
     */
    private ?string $username;

    /**
     * Date de création du profil de joueur
     *
     * @var DateTime
     */
    private ?DateTime $createdAt;

    /**
     * Date de mise à jour du profil de joueur
     *
     * @var DateTime|null
     */
    private ?DateTime $updatedAt;

    /**
     * Points d'expérience du joueur
     *
     * @var int|null
     */
    private ?int $xp;

    /**
     * Elo du joueur (le classement Elo attribue au player, selon ses performances, un certain nombre de points; deux players de même force possèdent, en théorie, un elo identique)
     *
     * @var int|null
     */
    private ?int $elo;

    /**
     * Nombre de Comus Coins possédés par le joueur (monnaie virtuelle)
     *
     * @var int|null
     */
    private ?int $comusCoins;

    /**
     * Statistiques du joueur
     *
     * @var Statistics|null
     */
    private ?Statistics $statistics;

    /**
     * Identifiant utilisateur (clé étrangère provenant de la table user)
     *
     * @var int|null
     */
    private ?int $userId;

    /**
     * Constructeur de la classe Player
     *
     * @param string $uuid
     * @param DateTime $createdAt
     * @param DateTime|null $updatedAt
     * @param int|null $xp
     * @param int|null $elo
     * @param int|null $comusCoins
     * @param Statistics|null $statistics
     * @param int|null $userId
     */
    public function __construct(
        ?string $uuid = null,
        ?DateTime $createdAt = null,
        ?DateTime $updatedAt = null,
        ?int $xp = null,
        ?int $elo = null,
        ?int $comusCoins = null,
        ?Statistics $statistics = null,
        ?int $userId = null
    ) {
        $this->uuid = $uuid;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->xp = $xp;
        $this->elo = $elo;
        $this->comusCoins = $comusCoins;
        $this->statistics = $statistics;
        $this->userId = $userId;
    }

    /**
     * Retourne l'UUID du joueur
     *
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * Modifie l'UUID du joueur
     *
     * @param string $uuid
     * @return void
     */
    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    /**
     * Retourne le nom d'utilisateur du joueur
     *
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Modifie le nom d'utilisateur du joueur
     *
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * Retourne la date de création du joueur
     *
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * Modifie la date de création du joueur
     *
     * @param DateTime $createdAt
     * @return void
     */
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Retourne la date de mise à jour du joueur
     *
     * @return DateTime|null
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
     * Modifie la date de mise à jour du joueur
     *
     * @param DateTime|null $updatedAt
     * @return void
     */
    public function setUpdatedAt(?DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Retourne les points d'expérience du joueur
     *
     * @return int|null
     */
    public function getXp(): ?int
    {
        return $this->xp;
    }

    /**
     * Modifie les points d'expérience du joueur
     *
     * @param int|null $xp
     * @return void
     */
    public function setXp(?int $xp): void
    {
        $this->xp = $xp;
    }

    /**
     * Retourne l'Elo du joueur
     *
     * @return int|null
     */
    public function getElo(): ?int
    {
        return $this->elo;
    }

    /**
     * Modifie l'Elo du joueur
     *
     * @param int|null $elo
     * @return void
     */
    public function setElo(?int $elo): void
    {
        $this->elo = $elo;
    }

    /**
     * Retourne le nombre de Comus Coins possédés par le joueur
     *
     * @return int|null
     */
    public function getComusCoins(): ?int
    {
        return $this->comusCoins;
    }

    /**
     * Modifie le nombre de Comus Coins possédés par le joueur
     *
     * @param int|null $comusCoins
     * @return void
     */
    public function setComusCoins(?int $comusCoins): void
    {
        $this->comusCoins = $comusCoins;
    }

    /**
     * Retourne les statistiques du joueur
     *
     * @return Statistics|null
     */
    public function getStatistics(): ?Statistics
    {
        return $this->statistics;
    }

    /**
     * Modifie les statistiques du joueur
     *
     * @param Statistics|null $statistics
     */
    public function setStatistics(?Statistics $statistics): void
    {
        $this->statistics = $statistics;
    }

    /**
     * Retourne l'identifiant utilisateur
     *
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * Modifie l'identifiant utilisateur
     *
     * @param int|null $userId
     * @return void
     */
    public function setUserId(?int $userId): void
    {
        $this->userId = $userId;
    }
}