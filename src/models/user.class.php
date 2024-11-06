<?php

class User {
    private ?int $id;
    private ?string $email;
    private ?DateTime $email_verified_at;
    private ?string $email_verifiy_token;
    private ?string $password;
    private ?int $disabled;
    private ?int $created_at;
    private ?int $updated_at;

    /**
     * Constructeur de la classe User
     *
     * @param int|null $id
     * @param string|null $email
     * @param DateTime|null $email_verified_at
     * @param string|null $email_verifiy_token
     * @param string|null $password
     * @param int|null $disabled
     * @param DateTime|null $created_at
     * @param DateTime|null $updated_at
     */
    public function __construct(?int $id = null, ?string $email = null, ?DateTime $email_verified_at = null, ?string $email_verifiy_token = null, ?string $password = null, ?int $disabled = null, ?int $created_at = null, ?int $updated_at = null)
    {
        $this->id = $id;
        $this->email = $email;
        $this->email_verified_at = $email_verified_at;
        $this->email_verifiy_token = $email_verifiy_token;
        $this->password = $password;
        $this->disabled = $disabled;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
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
        return $this->email_verified_at;
    }

    /**
     * Modifie la date de vérification de l'adresse email
     *
     * @param DateTime|null $email_verified_at
     */
    public function setEmailVerifiedAt(?DateTime $email_verified_at): void
    {
        $this->email_verified_at = $email_verified_at;
    }

    /**
     * Retourne le token de vérification de l'adresse email
     *
     * @return string|null
     */
    public function getEmailVerifiyToken(): ?string
    {
        return $this->email_verifiy_token;
    }

    /**
     * Modifie le token de vérification de l'adresse email
     *
     * @param string|null $email_verifiy_token
     */
    public function setEmailVerifiyToken(?string $email_verifiy_token): void
    {
        $this->email_verifiy_token = $email_verifiy_token;
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
     * @return int|null
     */
    public function getCreatedAt(): ?int
    {
        return $this->created_at;
    }

    /**
     * Modifie la date de création de l'utilisateur
     *
     * @param int|null $created_at
     */
    public function setCreatedAt(?int $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * Retourne la date de mise à jour de l'utilisateur
     *
     * @return int|null
     */
    public function getUpdatedAt(): ?int
    {
        return $this->updated_at;
    }

    /**
     * Modifie la date de mise à jour de l'utilisateur
     *
     * @param int|null $updated_at
     */
    public function setUpdatedAt(?int $updated_at): void
    {
        $this->updated_at = $updated_at;
    }
}