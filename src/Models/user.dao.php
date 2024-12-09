<?php
/**
 * @file    user.dao.php
 * @author  Estéban DESESSARD
 * @brief   Le fichier contient la déclaration & définition de la classe UserDAO.
 * @date    13/11/2024
 * @version 0.1
 */

namespace ComusParty\Models;

use DateMalformedStringException;
use DateTime;
use Exception;
use PDO;

/**
 * @brief Classe UserDAO
 * @details La classe UserDAO permet de gérer les utilisateurs en base de données
 */
class UserDAO {
    /**
     * @brief La connexion à la base de données
     * @var PDO|null
     */
    private ?PDO $pdo;

    /**
     * @brief Le constructeur de la classe UserDAO
     * @param PDO|null $pdo
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
     * @brief Retourne un objet User (ou null) à partir de l'ID passé en paramètre
     * @param int $id L'ID de l'utilisateur recherché
     * @return User|null Objet retourné par la méthode, ici un utilisateur (ou null si non-trouvé)
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
     */
    public function findById(int $id): ?User {
        $stmt = $this->pdo->prepare(
            'SELECT *
            FROM '. DB_PREFIX .'user
            WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $userTab = $stmt->fetch();
        if ($userTab === false) {
            return null;
        }
        return $this->hydrate($userTab);
    }

    /**
     * @brief Met à jour un utilisateur en base de données
     * @param User $user L'utilisateur à mettre à jour
     * @return bool Retourne true si la mise à jour a réussi, false sinon
     */
    public function update(User $user): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE '.DB_PREFIX.'user
            SET email = :email,
                email_verified_at = :email_verified_at,
                email_verif_token = :email_verif_token,
                password = :password,
                disabled = :disabled,
                created_at = :created_at
            WHERE id = :id'
        );

        $email = $user->getEmail();
        $verifiedAt = $user->getEmailVerifiedAt()->getTimestamp();
        $emailVerifyToken = $user->getEmailVerifyToken();
        $password = $user->getPassword();
        $disabled = $user->getDisabled();
        $createdAt = $user->getCreatedAt()->getTimestamp();
        $id = $user->getId();

        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':email_verified_at', $verifiedAt);
        $stmt->bindParam(':email_verif_token', $emailVerifyToken);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':disabled', $disabled);
        $stmt->bindParam(':created_at', $createdAt);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    /**
     * Retourne un utilisateur en fonction de son email
     *
     * @param string|null $email Email de l'utilisateur
     * @return User|null Objet retourné par la méthode, ici un utilisateur (ou null si non-trouvé)
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
     */
    public function findByEmail(?string $email) : ?User {
        $stmt = $this->pdo->prepare(
            'SELECT *
            FROM '. DB_PREFIX .'user
            WHERE email = :email');
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $userTab = $stmt->fetch();
        if ($userTab === false) {
            return null;
        }
        return $this->hydrate($userTab);
    }

    /**
     * @brief Hydrate un objet User à partir des données passées en paramètre
     * @param array $data Le tableau associatif contenant les données de l'utilisateur
     * @return User Objet retourné par la méthode, ici un utilisateur
     * @throws DateMalformedStringException|Exception Exception levée dans le cas d'une date malformée
     */
    public function hydrate(array $data): User {
        $user = new User();
        $user->setId($data['id']);
        $user->setEmail($data['email']);
        $user->setEmailVerifiedAt($data['email_verified_at'] ? new DateTime($data['email_verified_at']) : null);
        $user->setEmailVerifyToken($data['email_verif_token']);
        $user->setPassword($data['password']);
        $user->setDisabled($data['disabled']);
        $user->setCreatedAt(new DateTime($data['created_at']));
        $user->setUpdatedAt(new DateTime($data['updated_at']));
        return $user;
    }
}