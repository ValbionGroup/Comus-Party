<?php
/**
 * @file    user.dao.php
 * @author  Estéban DESESSARD
 * @brief   Le fichier contient la déclaration & définition de la classe UserDAO.
 * @date    13/11/2024
 * @version 0.1
 */

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
        $user = $this->hydrate($userTab);
        return $user;
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
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
     */
    public function hydrate(array $data): User {
        $user = new User();
        $user->setId($data['id']);
        $user->setEmail($data['email']);
        $user->setEmailVerifiedAt(new DateTime($data['email_verified_at']));
        $user->setEmailVerifyToken($data['email_verif_token']);
        $user->setPassword($data['password']);
        $user->setDisabled($data['disabled']);
        $user->setCreatedAt(new DateTime($data['created_at']));
        $user->setUpdatedAt(new DateTime($data['updated_at']));
        return $user;
    }
}