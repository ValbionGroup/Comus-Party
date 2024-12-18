<?php
/**
 * @file    invoice.dao.php
 * @author  Estéban DESESSARD
 * @brief   Le fichier contient la déclaration & définition de la classe InvoiceDAO.
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
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
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
}