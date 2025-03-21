<?php
/**
 * @file    invoice.dao.php
 * @author  Estéban DESESSARD
 * @brief   Fichier de déclaration et définition de la classe InvoiceDAO
 * @date    02/12/2024
 * @version 0.1
 */

namespace ComusParty\Models;

use DateMalformedStringException;
use DateTime;
use PDO;

/**
 * @brief Classe InvoiceDAO
 * @details La classe InvoiceDAO permet de faire des opérations en lien avec les factures sur la base de données
 */
class InvoiceDAO
{
    /**
     * @brief La connexion à la base de données
     * @var PDO|null
     */
    private ?PDO $pdo;


    /**
     * @brief Le constructeur de la classe InvoiceDAO
     * @param PDO|null $pdo La connexion à la base de données
     */
    public function __construct(?PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @brief Retourne un objet Invoice (ou null) à partir de l'ID passé en paramètre
     * @param int|null $id L'ID de la facture recherchée
     * @return Invoice|null
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
     */
    public function findById(?int $id): ?Invoice
    {
        $stmt = $this->pdo->prepare(
            'SELECT *
            FROM ' . DB_PREFIX . 'invoice
            WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $invoice = $stmt->fetch();
        if ($invoice === false) {
            return null;
        }
        return $this->hydrate($invoice);
    }

    /**
     * @param array $invoiceTab Tableau contenant les informations de la facture
     * @return Invoice Objet retourné par la méthode, ici une facture
     * @throws DateMalformedStringException Exceptions levée dans le cas d'une date malformée
     */
    public function hydrate(array $invoiceTab): Invoice
    {
        $invoice = new Invoice();
        $invoice->setId($invoiceTab['id']);
        $invoice->setCreatedAt(new DateTime($invoiceTab['created_at']));
        $invoice->setPlayerUuid($invoiceTab['player_uuid']);
        switch ($invoiceTab['payment_type']) {
            case 'card':
                $invoice->setPaymentType(PaymentType::Card);
                break;
            case 'paypal':
                $invoice->setPaymentType(PaymentType::PayPal);
                break;
            case 'comuscoins':
                $invoice->setPaymentType(PaymentType::ComusCoins);
                break;
            default:
                break;
        }
        return $invoice;
    }

    /**
     * @brief Crée une facture dans la base de données
     * @param string $player_uuid L'UUID du joueur ayant généré et payé la facture
     * @param string $payment_type Le moyen de paiement utilisé
     */
    public function createInvoice(string $player_uuid, string $payment_type): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO ' . DB_PREFIX . 'invoice (player_uuid, payment_type)
            VALUES (:player_uuid, :payment_type)');
        $stmt->bindParam(':player_uuid', $player_uuid);
        $stmt->bindParam(':payment_type', $payment_type);
        $stmt->execute();

        $invoice = $this->getPdo()->lastInsertId();
    }

    /**
     * @brief Retourne la connexion à la base de données
     * @return PDO|null Objet retourné par la méthode, ici un PDO représentant la connexion à la base de données
     */
    public function getPdo(): ?PDO
    {
        return $this->pdo;
    }

    /**
     * @brief Modifie la connexion à la base de données
     * @param PDO|null $pdo La nouvelle connexion à la base de données
     */
    public function setPdo(?PDO $pdo): void
    {
        $this->pdo = $pdo;
    }

    /**
     * @brief Crée une facture avec des articles dans la base de données
     * @param string $player_uuid L'UUID du joueur ayant généré et payé la facture
     * @param string $payment_type Le moyen de paiement utilisé
     * @param array $articles Les articles de la facture
     */
    public function createInvoiceWithArticles(string $player_uuid, string $payment_type, array $articles): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO ' . DB_PREFIX . 'invoice (player_uuid, payment_type)
        VALUES (:player_uuid, :payment_type)'
        );
        $stmt->bindParam(':player_uuid', $player_uuid);
        $stmt->bindParam(':payment_type', $payment_type);
        $stmt->execute();

        $invoiceId = $this->pdo->lastInsertId();

        $stmt = $this->pdo->prepare(
            'INSERT INTO ' . DB_PREFIX . 'invoice_row (invoice_id, article_id) VALUES (:invoice_id, :article_id)'
        );
        foreach ($articles as $article) {
            $stmt->bindParam(':invoice_id', $invoiceId);
            $stmt->bindParam(':article_id', $article);
            $stmt->execute();
        }
    }

}