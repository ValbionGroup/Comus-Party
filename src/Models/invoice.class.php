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
 *  - Card : paiement par carte bancaire
 *  - PayPal : paiement par PayPal
 *  - ComusCoins : paiement avec les ComusCoins (la monnaie virtuelle de la plateforme)
 * @enum paymentType
 */
enum PaymentType
{
    case Card;
    case PayPal;
    case ComusCoins;
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
     * @var PaymentType|null
     */
    private ?PaymentType $paymentType;

    /**
     * @brief La date de création de la facture
     * @var DateTime|null
     */
    private ?DateTime $createdAt;


    /**
     * @param int|null $id L'ID de la facture
     * @param string|null $playerUuid L'UUID du joueur ayant généré et payé la facture
     * @param PaymentType|null $paymentType Le moyen de paiement utilisé
     * @param DateTime|null $createdAt La date de création de la facture
     */
    public function __construct(?int $id = null, ?string $playerUuid = null, ?PaymentType $paymentType = null, ?DateTime $createdAt = null)
    {
        $this->id = $id;
        $this->playerUuid = $playerUuid;
        $this->paymentType = $paymentType;
        $this->createdAt = $createdAt;
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
     * @return PaymentType Le type de paiement
     */
    public function getPaymentType(): PaymentType
    {
        return $this->paymentType;
    }

    /**
     * @brief Modifie le type de paiement réalisé
     * @param PaymentType $paymentType Le nouveau type de paiement
     */
    public function setPaymentType(PaymentType $paymentType): void
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
}