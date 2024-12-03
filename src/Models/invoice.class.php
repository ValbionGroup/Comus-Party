<?php
/**
 * @file    invoice.class.php
 * @author  Estéban DESESSARD
 * @brief   Le fichier contient la déclaration & définition de la classe Invoice.
 * @date    02/12/2024
 * @version 0.2
 */

namespace ComusParty\Models;

use DateTime;

/**
 * @brief Enumération des types de paiement
 * @details Voici les différentes possibilités de paiement à l'heure actuelle :
 *  - card : paiement par carte bancaire
 *  - paypal : paiement par PayPal
 * @enum paymentType
 */
enum paymentType
{
    case card;
    case paypal;
}

/**
 * @brief Classe Invoice
 * @details La classe Invoice représente une facture réalisée dans le cadre d'un achat d'un service non-impactant par un utilisateur sur la plateforme
 */
class Invoice
{
    /**
     * @brief L'id de la facture - identifiant unique
     * @var int|null
     */
    private ?int $id;

    /**
     * @brief L'UUID du joueur ayant généré et payé la facture
     * @var string|null
     */
    private ?string $playerUuid;

    /**
     * @brief Le type de paiement réalisé
     * @var paymentType|null
     */
    private ?paymentType $paymentType;

    /**
     * @brief La date de création de la facture
     * @var DateTime|null
     */
    private ?DateTime $createdAt;

    /**
     * @brief Les articles de la facture
     * @var array|null
     */
    private ?array $articles;

    /**
     * @param int|null $id L'ID de la facture
     * @param string|null $playerUuid L'UUID du joueur ayant généré et payé la facture
     * @param paymentType|null $paymentType Le moyen de paiement utilisé
     * @param DateTime|null $createdAt La date de création de la facture
     */
    public function __construct(?int $id = null, ?string $playerUuid = null, ?paymentType $paymentType = null, ?DateTime $createdAt = null, ?array $articles = null)
    {
        $this->id = $id;
        $this->playerUuid = $playerUuid;
        $this->paymentType = $paymentType;
        $this->createdAt = $createdAt;
        $this->articles = $articles;
    }

    /**
     * @brief Retourne l'ID de la facture
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @brief Modifie l'ID de la facture
     * @param int $id Le nouvel ID de la facture
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @brief Retourne l'UUID du joueur ayant généré et payé la facture
     * @return string L'UUID du joueur
     */
    public function getPlayerUuid(): string
    {
        return $this->playerUuid;
    }

    /**
     * @brief Modifie l'UUID du joueur ayant généré et payé la facture
     * @param string $playerUuid Le nouvel UUID du joueur
     */
    public function setPlayerUuid(string $playerUuid): void
    {
        $this->playerUuid = $playerUuid;
    }

    /**
     * @brief Retourne le type de paiement réalisé
     * @return paymentType Le type de paiement
     */
    public function getPaymentType(): paymentType
    {
        return $this->paymentType;
    }

    /**
     * @brief Modifie le type de paiement réalisé
     * @param paymentType $paymentType Le nouveau type de paiement
     */
    public function setPaymentType(paymentType $paymentType): void
    {
        $this->paymentType = $paymentType;
    }

    /**
     * @brief Retourne la date de création de la facture
     * @return DateTime La date de création de la facture
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @brief Modifie la date de création de la facture
     * @param DateTime $createdAt La nouvelle date de création de la facture
     */
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @brief Retourne les articles de la facture
     * @return null|array Les articles de la facture
     */
    public function getArticles(): ?array
    {
        return $this->articles;
    }

    /**
     * @brief Modifie les articles de la facture
     * @param null|array $articles Les articles de la facture
     * @return void
     */
    public function setArticles(?array $articles): void
    {
        $this->articles = $articles;
    }

    /**
     * @brief Ajoute un article à l'attribut articles
     * @param Article $article L'article à ajouter
     * @return void
     */
    public function addArticle(Article $article): void
    {
        $key = in_array($article, $this->articles);
        if ($key !== false) {
            return;
        }
        $this->articles[] = $article;
    }

    /**
     * @brief Supprime un article de l'attribut articles
     * @param Article $article L'article à supprimer
     * @return void
     */
    public function removeArticle(Article $article): void
    {
        $key = array_search($article, $this->articles);
        if ($key !== false) {
            unset($this->articles[$key]);
        }
    }
}