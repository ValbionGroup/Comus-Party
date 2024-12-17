<?php
/**
 * @file    suggestion.dao.php
 * @author  Estéban DESESSARD
 * @brief   Le fichier contient la déclaration & définition de la classe SuggestionDAO.
 * @date    17/11/2024
 * @version 0.1
 */

namespace Models;

use ComusParty\Models\Suggestion;

/**
 * @brief Classe SuggestionDAO
 * @details La classe SuggestionDAO permet de faire des opérations sur la table suggestion dans la base de données
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
     * @brief Crée une suggestion dans la base de données
     * @param Suggestion $suggestion La suggestion à créer
     * @return void
     */
    public function create(Suggestion $suggestion): void
    {
        $stmt = $this->pdo->prepare('INSERT INTO ' . DB_PREFIX . ' (content, author_uuid) VALUES (:content, :author_uuid)');
        $stmt->bindParam(':content', $suggestion->getContent());
        $stmt->bindParam(':author_uuid', $suggestion->getAuthorUuid());
        $stmt->execute();
    }
}