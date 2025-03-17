<?php
/**
 * @brief Fichier de déclaration et définition de la classe RememberTokenDAO
 *
 * @file rememberToken.dao.php
 * @author Lucas ESPIET "espiet.l@valbion.com"
 * @version 1.0
 * @date 2025-02-26
 */

namespace ComusParty\Models;

use DateMalformedStringException;
use DateTime;
use PDO;

/**
 * @brief Classe rememberTokenDAO
 * @details La classe rememberTokenDAO permet de gérer les actions liées aux jetons de connexion dans la base de données
 */
class RememberTokenDAO
{
    /**
     * @brief La connexion à la base de données
     * @var PDO|null
     */
    private ?PDO $pdo;

    /**
     * @brief Le constructeur de la classe PlayerDAO
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
     * @brief Insère un jeton de connexion en base de données
     * @param RememberToken $rememberToken Le jeton de connexion à insérer
     * @return bool Retourne true si l'insertion a réussi, false sinon
     */
    public function insert(RememberToken $rememberToken): bool
    {
        $query = $this->pdo->prepare("INSERT INTO cp_remember_user (user_id, token, rmb_key, created_at, expires_at) VALUES (:user_id, :token, :rmb_key, :created_at, :expires_at)");

        $query->bindValue(":user_id", $rememberToken->getUserId());
        $query->bindValue(":token", $rememberToken->getToken());
        $query->bindValue(":rmb_key", $rememberToken->getKey());
        $query->bindValue(":created_at", $rememberToken->getCreatedAt()->format("Y-m-d H:i:s"));
        $query->bindValue(":expires_at", $rememberToken->getExpiresAt()->format("Y-m-d H:i:s"));

        return $query->execute();
    }

    /**
     * @brief Supprime un jeton de connexion en base de données
     * @param RememberToken $rememberToken Le jeton de connexion à supprimer
     * @return bool Retourne true si la suppression a réussi, false sinon
     */
    public function delete(RememberToken $rememberToken): bool
    {
        $query = $this->pdo->prepare("DELETE FROM cp_remember_user WHERE user_id = :user_id AND token = :token");

        $query->bindValue(":user_id", $rememberToken->getUserId());
        $query->bindValue(":token", $rememberToken->getToken());

        return $query->execute();
    }

    /**
     * @brief Récupère un jeton de connexion en base de données
     * @param int $userId Identifiant de l'utilisateur
     * @param string $token Jeton de connexion
     * @return RememberToken|null Retourne le jeton de connexion s'il existe, null sinon
     * @throws DateMalformedStringException Si la date de création ou d'expiration du jeton de connexion est mal formée
     */
    public function find(int $userId, string $token): ?RememberToken
    {
        $query = $this->pdo->prepare("SELECT * FROM cp_remember_user WHERE user_id = :user_id AND token = :token");

        $query->bindValue(":user_id", $userId);
        $query->bindValue(":token", $token);

        $query->execute();

        $data = $query->fetch();

        if ($data === false) {
            return null;
        }

        return $this->hydrate($data);
    }

    /**
     * @brief Hydrate un objet RememberToken avec les valeurs du tableau associatif passé en paramètre
     * @param array $data Tableau associatif contenant les données à hydrater
     * @return RememberToken Objet RememberToken hydraté
     * @throws DateMalformedStringException Si la date de création ou d'expiration du jeton de connexion est mal formée
     */
    private function hydrate(array $data): RememberToken
    {
        return new RememberToken(
            $data['user_id'],
            $data['token'],
            $data['rmb_key'],
            new DateTime($data['created_at']),
            new DateTime($data['expires_at'])
        );
    }

    /**
     * @brief Récupère un jeton de connexion en base de données pour un utilisateur
     * @param int $userId Identifiant de l'utilisateur
     * @return array|null Retourne les jetons de connexion s'ils existent, null sinon
     * @throws DateMalformedStringException Si la date de création ou d'expiration du jeton de connexion est mal formée
     */
    public function findByUserId(int $userId): ?array
    {
        $query = $this->pdo->prepare("SELECT * FROM cp_remember_user WHERE user_id = :user_id");

        $query->bindValue(":user_id", $userId);

        $query->execute();

        $data = $query->fetch();

        if ($data === false) {
            return null;
        }

        return $this->hydrateMany($data);
    }

    /**
     * @brief Hydrate un tableau d'objets RememberToken avec les valeurs des tableaux associatifs du tableau passé en paramètre
     * @param array $datas Tableau associatif contenant les données à hydrater
     * @return array Objet RememberToken hydraté
     * @throws DateMalformedStringException Si la date de création ou d'expiration du jeton de connexion est mal formée
     */
    private function hydrateMany(array $datas): array
    {
        $rememberTokens = [];
        foreach ($datas as $data) {
            $rememberTokens[] = $this->hydrate($data);
        }
        return $rememberTokens;
    }

    /**
     * @brief Supprime les jetons de connexion en base de données pour un utilisateur
     * @param int $userId Identifiant de l'utilisateur
     * @return bool Retourne true si la suppression a réussi, false sinon
     */
    public function deleteAllForUserId(int $userId): bool
    {
        $query = $this->pdo->prepare("DELETE FROM cp_remember_user WHERE user_id = :user_id");

        $query->bindValue(":user_id", $userId);

        return $query->execute();
    }
}