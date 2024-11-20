<?php
/**
 * @file    user.class.php
 * @author  Estéban DESESSARD
 * @brief   Le fichier contient la déclaration & définition de la classe User.
 * @date    12/11/2024
 * @version 0.1
 */

namespace ComusParty\Models;

/**
 * @brief Classe User
 * @details La classe User permet de représenter un utilisateur
 */
class User {
    /**
     * @brief L'identifiant de l'utilisateur
     * @var int|null
     */
    private ?int $id;

    /**
     * @brief L'adresse email de l'utilisateur
     * @var string|null
     */
    private ?string $email;

    /**
     * @brief La date de vérification de l'adresse email
     * @var DateTime|null
     */
    private ?DateTime $emailVerifiedAt;

    /**
     * @brief Le token de vérification de l'adresse email
     * @var string|null
     */
    private ?string $emailVerifyToken;

    /**
     * @brief Le mot de passe de l'utilisateur (hashé)
     * @var string|null
     */
    private ?string $password;

    /**
     * @brief L'état de l'utilisateur (désactivé ou non)
     * @details 0 = activé, 1 = désactivé
     * @var int|null
     */
    private ?int $disabled;

    /**
     * @brief La date de création de l'utilisateur
     * @var DateTime|null
     */
    private ?DateTime $createdAt;

    /**
     * @brief La date de mise à jour de l'utilisateur
     * @var DateTime|null
     */
    private ?DateTime $updatedAt;

    /**
     * @brief Le constructeur de la classe User
     * @param int|null $id L'identifiant de l'utilisateur
     * @param string|null $email L'adresse email de l'utilisateur
     * @param DateTime|null $emailVerifiedAt La date de vérification de l'adresse email
     * @param string|null $emailVerifyToken Le token de vérification de l'adresse email
     * @param string|null $password Le mot de passe de l'utilisateur (hashé)
     * @param int|null $disabled L'état de l'utilisateur (désactivé ou non)
     * @param DateTime|null $created_at La date de création de l'utilisateur
     * @param DateTime|null $updated_at La date de mise à jour de l'utilisateur
     */
    public function __construct(
        ?int $id = null,
        ?string $email = null,
        ?DateTime $emailVerifiedAt = null,
        ?string $emailVerifyToken = null,
        ?string $password = null,
        ?int $disabled = null,
        ?DateTime $created_at = null,
        ?DateTime $updated_at = null
    )
    {
        $this->id = $id;
        $this->email = $email;
        $this->emailVerifiedAt = $emailVerifiedAt;
        $this->emailVerifyToken = $emailVerifyToken;
        $this->password = $password;
        $this->disabled = $disabled;
        $this->createdAt = $created_at;
        $this->updatedAt = $updated_at;
    }

    /**
     * @brief Retourne l'identifiant de l'utilisateur
     * @return int|null Objet retourné par la fonction, ici un entier représentant l'identifiant de l'utilisateur
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @brief Modifie l'identifiant de l'utilisateur
     * @param int|null $id Le nouvel identifiant de l'utilisateur
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @brief Retourne l'adresse email de l'utilisateur
     * @return string|null Objet retourné par la fonction, ici une chaîne de caractères représentant l'adresse email de l'utilisateur
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @brief Modifie l'adresse e-mail de l'utilisateur
     * @param string|null $email La nouvelle adresse e-mail de l'utilisateur
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @brief Retourne la date de vérification de l'adresse e-mail
     * @return DateTime|null Objet retourné par la fonction, ici un objet DateTime représentant la date de vérification de l'adresse e-mail
     */
    public function getEmailVerifiedAt(): ?DateTime
    {
        return $this->emailVerifiedAt;
    }

    /**
     * @brief Modifie la date de vérification de l'adresse e-mail
     * @param DateTime|null $emailVerifiedAt La nouvelle date de vérification de l'adresse e-mail
     */
    public function setEmailVerifiedAt(?DateTime $emailVerifiedAt): void
    {
        $this->emailVerifiedAt = $emailVerifiedAt;
    }

    /**
     * @brief Retourne le token de vérification de l'adresse email
     * @return string|null Objet retourné par la fonction, ici une chaîne de caractères représentant le token de vérification de l'adresse email
     */
    public function getEmailVerifyToken(): ?string
    {
        return $this->emailVerifyToken;
    }

    /**
     * @brief Modifie le token de vérification de l'adresse email
     * @param string|null $emailVerifyToken Le nouveau token de vérification de l'adresse email
     */
    public function setEmailVerifyToken(?string $emailVerifyToken): void
    {
        $this->emailVerifyToken = $emailVerifyToken;
    }

    /**
     * @brief Retourne le mot de passe de l'utilisateur
     * @return string|null Objet retourné par la fonction, ici une chaîne de caractères représentant le mot de passe de l'utilisateur
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @brief Modifie le mot de passe de l'utilisateur
     * @param string|null $password Le nouveau mot de passe de l'utilisateur
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    /**
     * @brief Retourne l'état de l'utilisateur (activé ou désactivé)
     * @return int|null Objet retourné par la fonction, ici un entier représentant l'état de l'utilisateur (activé ou désactivé)
     */
    public function getDisabled(): ?int
    {
        return $this->disabled;
    }

    /**
     * @brief Modifie l'état de l'utilisateur (activé ou désactivé)
     * @param int|null $disabled Le nouvel état de l'utilisateur (activé ou désactivé)
     */
    public function setDisabled(?int $disabled): void
    {
        $this->disabled = $disabled;
    }

    /**
     * @brief Retourne la date de création de l'utilisateur
     * @return DateTime|null Objet retourné par la fonction, ici un objet DateTime représentant la date de création de l'utilisateur
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * @brief Modifie la date de création de l'utilisateur
     * @param DateTime|null $createdAt La nouvelle date de création de l'utilisateur
     */
    public function setCreatedAt(?DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @brief Retourne la date de mise à jour de l'utilisateur
     * @return DateTime|null Objet retourné par la fonction, ici un objet DateTime représentant la date de mise à jour de l'utilisateur
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @brief Modifie la date de mise à jour de l'utilisateur
     * @param DateTime|null $updatedAt La nouvelle date de mise à jour de l'utilisateur
     */
    public function setUpdatedAt(?DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}