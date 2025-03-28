<?php
/**
 * @file    player.class.php
 * @author  Estéban DESESSARD
 * @brief   Fichier de déclaration et définition de la classe Player
 * @date    12/11/2024
 * @version 0.1
 */

namespace ComusParty\Models;

use DateTime;

/**
 * @brief Classe Player
 * @details La classe Player représente un joueur de l'application
 */
class Player
{
    /**
     * @brief L'UUID du joueur, identifiant unique
     * @var string|null
     */
    private ?string $uuid;

    /**
     * @brief Le nom d'utilisateur du joueur
     * @var string|null
     */
    private ?string $username;

    /**
     * @brief La date de création du profil de joueur
     * @var DateTime|null
     */
    private ?DateTime $createdAt;

    /**
     * @brief La date de mise à jour du profil de joueur
     * @var DateTime|null
     */
    private ?DateTime $updatedAt;

    /**
     * @brief Les points d'expérience du joueur
     * @var int|null
     */
    private ?int $xp;

    /**
     * @brief Elo du joueur
     * @details Le classement Elo attribue au player, selon ses performances, un certain nombre de points; deux players de même force possèdent, en théorie, un elo identique.
     * @var int|null
     */
    private ?int $elo;

    /**
     * @brief Le nombre de Comus Coins possédés par le joueur
     * @details Les Comus Coins sont la monnaie virtuelle de l'application, ils permettent l'achat de service non-impactant sur l'expérience de jeu, tels que des avatars ou des bannières afin de personnaliser son profil.
     * @var int|null
     */
    private ?int $comusCoin;

    /**
     * @brief Statistiques du joueur
     *
     * @var Statistics|null
     */
    private ?Statistics $statistics;

    /**
     * @brief L'identifiant utilisateur lié au profil de joueur
     * @var int|null
     */
    private ?int $userId;

    /**
     * @brief L'avatar actif du joueur
     * @var string|null
     */
    private ?string $activePfp;

    /**
     * @brief La bannière active du joueur
     * @var string|null
     */
    private ?string $activeBanner;


    /**
     * @brief Le constructeur de la classe Player
     * @param string|null $uuid L'UUID du joueur
     * @param string|null $username Le nom d'utilisateur du joueur
     * @param DateTime|null $createdAt La date de création du joueur
     * @param DateTime|null $updatedAt La date de mise à jour du joueur
     * @param int|null $xp Les points d'expérience du joueur
     * @param int|null $elo L'Elo du joueur
     * @param int|null $comusCoins Le nombre de Comus Coins possédés par le joueur
     * @param Statistics|null $statistics Les statistiques du joueur
     * @param int|null $userId L'identifiant utilisateur lié au profil de joueur
     * @param string|null $activePfp L'avatar actif du joueur
     * @param string|null $activeBanner La bannière active du joueur
     */
    public function __construct(
        ?string     $uuid = null,
        ?string     $username = null,
        ?DateTime   $createdAt = null,
        ?DateTime   $updatedAt = null,
        ?int        $xp = null,
        ?int        $elo = null,
        ?int        $comusCoins = null,
        ?Statistics $statistics = null,
        ?int        $userId = null,
        ?string     $activePfp = null,
        ?string     $activeBanner = null
    )
    {
        $this->uuid = $uuid;
        $this->username = $username;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->xp = $xp;
        $this->elo = $elo;
        $this->comusCoin = $comusCoins;
        $this->statistics = $statistics;
        $this->userId = $userId;
        $this->activePfp = $activePfp;
        $this->activeBanner = $activeBanner;
    }

    /**
     * @brief Retourne l'UUID du joueur
     * @return string Objet retourné par la fonction, ici une chaîne de caractères représentant l'UUID du joueur
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @brief Modifie l'UUID du joueur
     * @param string|null $uuid Le nouvel UUID du joueur
     * @return void
     */
    public function setUuid(?string $uuid): void
    {
        $this->uuid = $uuid;
    }

    /**
     * @brief Retourne le nom d'utilisateur du joueur
     * @return string Objet retourné par la fonction, ici une chaîne de caractères représentant le nom d'utilisateur du joueur
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @brief Modifie le nom d'utilisateur du joueur
     * @param string|null $username Le nouveau nom d'utilisateur du joueur
     * @return void
     */
    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    /**
     * @brief Retourne la date de création du joueur
     * @return DateTime Objet retourné par la fonction, ici un objet DateTime représentant la date de création du joueur
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @brief Modifie la date de création du joueur
     * @param DateTime|null $createdAt La nouvelle date de création du joueur
     * @return void
     */
    public function setCreatedAt(?DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @brief Retourne la date de mise à jour du joueur
     * @return DateTime|null Objet retourné par la fonction, ici un objet DateTime représentant la date de mise à jour du joueur
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @brief Modifie la date de mise à jour du joueur
     * @param DateTime|null $updatedAt La nouvelle date de mise à jour du joueur
     * @return void
     */
    public function setUpdatedAt(?DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @brief Retourne les points d'expérience du joueur
     * @return int|null Objet retourné par la fonction, ici un entier représentant les points d'expérience du joueur
     */
    public function getXp(): ?int
    {
        return $this->xp;
    }

    /**
     * @brief Modifie les points d'expérience du joueur
     * @param int|null $xp Les nouveaux points d'expérience du joueur
     * @return void
     */
    public function setXp(?int $xp): void
    {
        $this->xp = $xp;
    }

    /**
     * @brief Retourne l'Elo du joueur
     * @return int|null Objet retourné par la fonction, ici un entier représentant l'Elo du joueur
     */
    public function getElo(): ?int
    {
        return $this->elo;
    }

    /**
     * @brief Modifie l'Elo du joueur
     * @param int|null $elo Le nouvel Elo du joueur
     * @return void
     */
    public function setElo(?int $elo): void
    {
        $this->elo = $elo;
    }

    /**
     * @brief Retourne le nombre de Comus Coins possédés par le joueur
     * @return int|null Objet retourné par la fonction, ici un entier représentant le nombre de Comus Coins possédés par le joueur
     */
    public function getComusCoin(): ?int
    {
        return $this->comusCoin;
    }

    /**
     * @brief Modifie le nombre de Comus Coins possédés par le joueur
     * @param int|null $comusCoin Le nouveau nombre de Comus Coins possédés par le joueur
     * @return void
     */
    public function setComusCoin(?int $comusCoin): void
    {
        $this->comusCoin = $comusCoin;
    }

    /**
     * @brief Retourne les statistiques du joueur
     * @return Statistics|null Objet retourné par la fonction, ici un objet Statistics représentant les statistiques du joueur
     */
    public function getStatistics(): ?Statistics
    {
        return $this->statistics;
    }

    /**
     * @brief Modifie les statistiques du joueur
     * @param Statistics|null $statistics Les nouvelles statistiques du joueur
     */
    public function setStatistics(?Statistics $statistics): void
    {
        $this->statistics = $statistics;
    }

    /**
     * @brief Retourne l'identifiant utilisateur
     * @return int|null Objet retourné par la fonction, ici un entier représentant l'identifiant utilisateur
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * @brief Modifie l'identifiant utilisateur
     * @param int|null $userId Le nouvel identifiant utilisateur
     * @return void
     */
    public function setUserId(?int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @brief Retourne le chemin d'accès vers l'avatar actif du joueur
     * @return string|null Chemin d'accès
     */
    public function getActivePfp(): ?string
    {
        return $this->activePfp;
    }

    /**
     * @brief Modifie le chemin d'accès vers l'avatar actif du joueur
     * @param string|null $activePfp Nouveau chemin d'accès
     * @return void
     */
    public function setActivePfp(?string $activePfp): void
    {
        $this->activePfp = $activePfp;
    }

    /**
     * @brief Retourne le chemin d'accès vers la bannière active du joueur
     * @return string|null Chemin d'accès
     */
    public function getActiveBanner(): ?string
    {
        return $this->activeBanner;
    }

    /**
     * @brief Modifie le chemin d'accès vers la bannière active du joueur
     * @param string|null $activeBanner Nouveau chemin d'accès
     * @return void
     */
    public function setActiveBanner(?string $activeBanner): void
    {
        $this->activeBanner = $activeBanner;
    }


    /**
     * @brief Convertit l'objet en tableau
     * @return array Objet retourné par la fonction, ici un tableau associatif représentant l'objet
     */
    public function toArray(): array
    {
        return [
            "uuid" => $this->uuid,
            "username" => $this->username,
            "createdAt" => $this->createdAt,
            "updatedAt" => $this->updatedAt,
            "xp" => $this->xp,
            "elo" => $this->elo,
            "comusCoin" => $this->comusCoin,
            "statistics" => $this->statistics->toArray(),
            "userId" => $this->userId,
            "activePfp" => $this->activePfp
        ];
    }
}