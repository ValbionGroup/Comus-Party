<?php
/**
 * @file    invoice.dao.php
 * @author  Estéban DESESSARD
 * @brief   Le fichier contient la déclaration & définition de la classe InvoiceDAO.
 * @date    02/12/2024
 * @version 0.1
 */

namespace ComusParty\Models;

use PDO;

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

    public function hydrate(array $invoiceTab): Invoice
    {
        $invoice = new Invoice();
        $invoice->setId($invoiceTab['id']);
        $invoice->setCreatedAt($invoiceTab['created_at']);
        $invoice->setPlayerUuid($invoiceTab['player_uuid']);
        return $invoice;
    }
}