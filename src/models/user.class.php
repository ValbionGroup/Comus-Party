<?php

class User {
    private ?int $id;
    private ?string $email;
    private ?DateTime $emailVerifiedAt;
    private ?string $emailVerifyToken;
    private ?string $password;
    private ?int $disabled;
    private ?DateTime $createdAt;
    private ?DateTime $updatedAt;

    /**
     * Constructeur de la classe User
     *
     * @param int|null $id
     * @param string|null $email
     * @param DateTime|null $emailVerifiedAt
     * @param string|null $emailVerifyToken
     * @param string|null $password
     * @param int|null $disabled
     * @param DateTime|null $created_at
     * @param DateTime|null $updated_at
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
     * Retourne l'identifiant de l'utilisateur
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Modifie l'identifiant de l'utilisateur
     *
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * Retourne l'adresse email de l'utilisateur
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Modifie l'adresse email de l'utilisateur
     *
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * Retourne la date de vérification de l'adresse email
     *
     * @return DateTime|null
     */
    public function getEmailVerifiedAt(): ?DateTime
    {
        return $this->emailVerifiedAt;
    }

    /**
     * Modifie la date de vérification de l'adresse email
     *
     * @param DateTime|null $emailVerifiedAt
     */
    public function setEmailVerifiedAt(?DateTime $emailVerifiedAt): void
    {
        $this->emailVerifiedAt = $emailVerifiedAt;
    }

    /**
     * Retourne le token de vérification de l'adresse email
     *
     * @return string|null
     */
    public function getEmailVerifyToken(): ?string
    {
        return $this->emailVerifyToken;
    }

    /**
     * Modifie le token de vérification de l'adresse email
     *
     * @param string|null $emailVerifyToken
     */
    public function setEmailVerifyToken(?string $emailVerifyToken): void
    {
        $this->emailVerifyToken = $emailVerifyToken;
    }

    /**
     * Retourne le mot de passe de l'utilisateur
     *
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Modifie le mot de passe de l'utilisateur
     *
     * @param string|null $password
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    /**
     * Retourne l'état de l'utilisateur (activé ou désactivé)
     *
     * @return int|null
     */
    public function getDisabled(): ?int
    {
        return $this->disabled;
    }

    /**
     * Modifie l'état de l'utilisateur (activé ou désactivé)
     *
     * @param int|null $disabled
     */
    public function setDisabled(?int $disabled): void
    {
        $this->disabled = $disabled;
    }

    /**
     * Retourne la date de création de l'utilisateur
     *
     * @return DateTime|null
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * Modifie la date de création de l'utilisateur
     *
     * @param DateTime|null $createdAt
     */
    public function setCreatedAt(?DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Retourne la date de mise à jour de l'utilisateur
     *
     * @return DateTime|null
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
     * Modifie la date de mise à jour de l'utilisateur
     *
     * @param DateTime|null $updatedAt
     */
    public function setUpdatedAt(?DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}