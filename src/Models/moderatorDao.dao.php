<?php

/**
 * @file moderatorDao.dao.php
 * @author Conchez-Boueytou Robin
 * @brief Le fichier contient la déclaration & définition de la classe ModeratorDAO.
 * @details
 * @date 18/12/2024
 * @version 0.1
 */

namespace ComusParty\Models;

use DateMalformedStringException;
use DateTime;
use PDO;

/**
 * @brief Classe ModeratorDAO
 * @details La classe ModeratorDAO permet de faire des opérations sur la table moderator dans la base de données
 */
class ModeratorDao
{
    /**
     * @brief La connexion à la base de données
     * @var PDO|null
     */
    private ?PDO $pdo;

    /**
     * @brief Le constructeur de la classe ModeratorDAO
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
     * @brief Retourne un objet Moderator (ou null) à partir de l'UUID passé en paramètre
     * @param string $uuid L'UUID du modérateur recherché
     * @return Moderator|null Objet retourné par la méthode, ici un modérateur (ou null si non-trouvé)
     * @throws DateMalformedStringException
     */
    public function findBuUuid(string $uuid): ?Moderator
    {
        $stmt = $this->pdo->prepare('SELECT * FROM '. DB_PREFIX .' moderator WHERE uuid = :uuid');
        $stmt->bindParam(':uuid', $uuid);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $moderatorTab = $stmt->fetch();
        if ($moderatorTab === false) {
            return null;
        }
        return $this->hydrate($moderatorTab);
    }

    /**
     * @brief Hydrate un objet Moderator avec les valeurs du tableau associatif passé en paramètre
     * @param array $data Le tableau associatif content les paramètres
     * @return Moderator|null L'objet retourné par la méthode, ici un joueur
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
     */
    public function hydrate(array $data): ?Moderator
    {
        $moderator = new Moderator();
        $moderator->setUuid($data['uuid']);
        $moderator->setUserId($data['user_id']);
        $moderator->setFirstName($data['first_name']);
        $moderator->setLastName($data['last_name']);
        $moderator->setCreatedAt(new DateTime($data['created_at']));
        $moderator->setUpdatedAt(new DateTime($data['updated_at']));
        return $moderator;
    }

}