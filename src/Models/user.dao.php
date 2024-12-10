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
        // Dates de création et de mise à jour de l'utilisateur avec un objet DateTime
        $user->setCreatedAt(new DateTime($data['created_at']));
        $user->setUpdatedAt(new DateTime($data['updated_at']));
        return $user;
    }

    /**
     * @brief Retourne un objet User (ou null) à partir du token de vérification d'email passé en paramètre
     * @param string $emailVerifToken Le token de vérification d'email de l'utilisateur
     * @return User|null Objet retourné par la méthode, ici un utilisateur (ou null si non-trouvé)
     */
    public function findByEmailVerifyToken(string $emailVerifToken): ?User {
        $stmt = $this->pdo->prepare(
            'SELECT *
            FROM '. DB_PREFIX .'user
            WHERE email_verif_token = :email_verif_token');
        $stmt->bindParam(':email_verif_token', $emailVerifToken);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $userTab = $stmt->fetch();
        if ($userTab === false) {
            return null;
        }
        return $this->hydrate($userTab);
    }


    /**
     * @brief Crée un utilisateur en base de données
     * @param string $email L'adresse e-mail de l'utilisateur
     * @param string $password Le mot de passe de l'utilisateur
     * @param string $emailVerifToken Le token de verification de l'utilisateur
     * @return bool Retourne true si l'utilisateur a pu être créé, false sinon
     */
    public function createUser(string $email, string $password, string $emailVerifToken): bool  {
            $stmtUser = $this->pdo->prepare("INSERT INTO " . DB_PREFIX . "user (email, password, email_verified_at, email_verif_token, disabled) VALUES (:email, :password, null, :email_verif_token, 0)");
            $stmtUser->bindParam(':email', $email);
            $stmtUser->bindParam(':password', $password);
            $stmtUser->bindParam(':email_verif_token', $emailVerifToken);
            return $stmtUser->execute();
    }

    /**
     * @brief Confirme un utilisateur en mettant à jour la date de confirmation et en supprimant le token de vérification d'email
     * @param string $emailVerifToken Le token de vérification d'email de l'utilisateur
     * @return bool Retourne true si l'utilisateur a pu être confirmé, false sinon
     */
    public function confirmUser(string $emailVerifToken): bool  {
        $stmtUser = $this->pdo->prepare("UPDATE " . DB_PREFIX . "user SET email_verified_at = now(), email_verif_token = null WHERE email_verif_token = :email_verif_token");
        $stmtUser->bindParam(':email_verif_token', $emailVerifToken);
        return $stmtUser->execute();
    }

}