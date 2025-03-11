<?php
/**
 * @brief Fichier de déclaration et définition de la classe PasswordResetTokenDAO
 *
 * @file passwordResetToken.dao.php
 * @author Lucas ESPIET "espiet.l@valbion.com"
 * @version 1.0
 * @date 2024-12-04
 */

namespace ComusParty\Models;

use DateMalformedStringException;
use DateTime;
use PDO;

/**
 * @brief Classe PasswordResetTokenDAO
 * @details La classe PasswordResetTokenDAO permet de gérer les tokens de réinitialisation de mot de passe en base de données
 */
class PasswordResetTokenDAO
{
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
     * @brief Retourne un objet PasswordResetToken (ou null) à partir de l'ID passé en paramètre
     * @param int $userId L'ID de l'utilisateur recherché
     * @return PasswordResetToken|null Objet retourné par la méthode, ici un token de réinitialisation de mot de passe (ou null si non-trouvé)
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
     */
    public function findByUserId(int $userId): ?PasswordResetToken
    {
        $stmt = $this->pdo->prepare('SELECT * FROM ' . DB_PREFIX . 'pswd_reset_token WHERE user_id = :userId');
        $stmt->execute(['userId' => $userId]);
        $row = $stmt->fetch();
        if ($row === false) {
            return null;
        }
        return $this->hydrate($row);
    }

    /**
     * @brief Hydrate un tableau de données en un objet PasswordResetToken
     * @param array $row Le tableau de données à hydrater
     * @return PasswordResetToken Objet PasswordResetToken retourné par la méthode
     * @throws DateMalformedStringException Exception levée dans le cas d'une date incorrecte
     */
    private function hydrate(array $row): PasswordResetToken
    {
        return new PasswordResetToken($row['user_id'], $row['token'], new DateTime($row['created_at']));
    }

    /**
     * @brief Retourne un objet PasswordResetToken (ou null) à partir du token passé en paramètre
     * @param string $token Le token de réinitialisation de mot de passe recherché
     * @return PasswordResetToken|null Objet PasswordResetToken (ou null si non-trouvé)
     * @throws DateMalformedStringException Exception levée dans le cas d'une date incorrecte
     */
    public function findByToken(string $token): ?PasswordResetToken
    {
        $stmt = $this->pdo->prepare('SELECT * FROM ' . DB_PREFIX . 'pswd_reset_token WHERE token = :token');
        $stmt->execute(['token' => $token]);
        $row = $stmt->fetch();
        if ($row === false) {
            return null;
        }
        return $this->hydrate($row);
    }

    /**
     * @brief Insère un token de réinitialisation de mot de passe en base de données
     * @param PasswordResetToken $token Le token à insérer
     * @return bool Vrai si l'insertion a réussi, faux sinon
     */
    public function insert(PasswordResetToken $token): bool
    {
        $stmt = $this->pdo->prepare('INSERT INTO ' . DB_PREFIX . 'pswd_reset_token (user_id, token, created_at) VALUES (:userId, :token, :createdAt)');
        return $stmt->execute([
            'userId' => $token->getUserId(),
            'token' => $token->getToken(),
            'createdAt' => $token->getCreatedAt()->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * @brief Supprime un token de réinitialisation de mot de passe en base de données
     * @param int $userId L'ID de l'utilisateur
     * @return bool Vrai si la suppression a réussi, faux sinon
     */
    public function delete(int $userId): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM ' . DB_PREFIX . 'pswd_reset_token WHERE user_id = :userId');
        return $stmt->execute(['userId' => $userId]);
    }

    /**
     * @brief Hydrate un tableau de données en un tableau d'objets PasswordResetToken
     * @param array $rows Le tableau de données à hydrater
     * @return array Objet PasswordResetToken retourné par la méthode
     * @throws DateMalformedStringException
     */
    private function hydrateMany(array $rows): array
    {
        $tokens = [];
        foreach ($rows as $row) {
            $tokens[] = $this->hydrate($row);
        }
        return $tokens;
    }
}