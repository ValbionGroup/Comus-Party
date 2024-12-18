<?php
/**
 * @file    suggestion.dao.php
 * @author  Estéban DESESSARD
 * @brief   Le fichier contient la déclaration & définition de la classe SuggestionDAO.
 * @date    17/12/2024
 * @version 0.1
 */

namespace ComusParty\Models;

use PDO;

/**
 * @brief Classe SuggestionDAO
 * @details La classe SuggestionDAO permet de gérer les suggestions en base de données
 */
class SuggestionDAO
{
    /**
     * @brief La connexion à la base de données
     * @var PDO|null
     */
    private ?PDO $pdo;

    /**
     * @brief Le constructeur de la classe SuggestionDAO
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
     * @brief Insert une suggestion en base de données et retourne le résultat de l'exécution
     * @param Suggestion $suggestion La suggestion à insérer
     * @return bool
     */
    public function create(Suggestion $suggestion): bool
    {
        $stmt = $this->pdo->prepare('INSERT INTO ' . DB_PREFIX . 'suggestion (content, author_uuid, created_at) VALUES (:content, :author_uuid, :created_at)');
        $content = $suggestion->getContent();
        $authorUuid = $suggestion->getAuthorUuid();
        $createdAt = $suggestion->getCreatedAt();
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':author_uuid', $authorUuid);
        $stmt->bindParam(':created_at', $createdAt);
        return $stmt->execute();
    }

    public function findAll()
    {

    }
}