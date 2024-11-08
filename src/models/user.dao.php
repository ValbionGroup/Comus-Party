<?php

/**
 * La classe UserDAO permet de gérer les utilisateurs en base de données
 */
class UserDAO {
    /**
     * Connexion à la base de données
     *
     * @var PDO|null
     */
    private ?PDO $pdo;

    /**
     * Constructeur de la classe UserDAO
     *
     * @param PDO|null $pdo
     */
    public function __construct(?PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Retourne la connexion à la base de données
     *
     * @return PDO|null
     */
    public function getPdo(): ?PDO
    {
        return $this->pdo;
    }

    /**
     * Modifie la connexion à la base de données
     *
     * @param PDO|null $pdo
     */
    public function setPdo(?PDO $pdo): void
    {
        $this->pdo = $pdo;
    }

    /**
     * Retourne un utilisateur en fonction de son identifiant
     *
     * @param int $id
     * @return User|null
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
     * @param string|null $email
     * @return User|null
     * @throws DateMalformedStringException
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
        $user = $this->hydrate($userTab);
        return $user;
    }

    /**
     * Génère un objet User à valorisé avec les valeurs fournies en paramètre
     *
     * @param array $data
     * @return User
     * @throws DateMalformedStringException
     */
    public function hydrate(array $data): User {
        $user = new User();
        $user->setId($data['id']);
        $user->setEmail($data['email']);
        $user->setEmailVerifiedAt(new DateTime($data['email_verified_at']));
        $user->setEmailVerifyToken($data['email_verify_token']);
        $user->setPassword($data['password']);
        $user->setDisabled($data['disabled']);
        $user->setCreatedAt(new DateTime($data['created_at']));
        $user->setUpdatedAt(new DateTime($data['updated_at']));
        return $user;
    }
}