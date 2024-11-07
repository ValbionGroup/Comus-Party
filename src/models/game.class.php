<?php

/**
 * Objet représentant l'état d'un jeu
 */
enum State {
    case AVAILABLE;
    case UNVAILABLE;
    case MAINTENANCE;
}

/**
 * Objet représentant un jeu
 */
class Game
{
    /**
     * Identifiant du jeu
     *
     * @var int|null
     */
    private ?int $id;
    /**
     * État du jeu
     *
     * @var State|null
     */
    /**
     * Nom du jeu
     *
     * @var string|null
     */
    private ?string $name;
    /**
     * Description du jeu
     *
     * @var string|null
     */
    private ?string $description;
    /**
     * Image du jeu
     *
     * @var string|null
     */
    private ?string $pathImg;
    private ?State $state;
    /**
     * Date de création du jeu
     *
     * @var DateTime|null
     */
    private ?DateTime $createdAt;
    /**
     * Date de mise à jour du jeu
     *
     * @var DateTime|null
     */
    private ?DateTime $updatedAt;

    /**
     * @param int|null $id
     * @param string|null $name
     * @param string|null $description
     * @param string|null $pathImg
     * @param State|null $state
     * @param DateTime|null $createdAt
     * @param DateTime|null $updatedAt
     */
    public function __construct(
        ?int $id = null,
        ?string $name = null,
        ?string $description = null,
        ?string $pathImg = null,
        ?State $state = null,
        ?DateTime $createdAt = null,
        ?DateTime $updatedAt = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->pathImg = $pathImg;
        $this->state = $state;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    /**
     * Retourne l'identifiant du jeu
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Modifie l'identifiant du jeu
     *
     * @param int|null $id
     * @return void
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * Retourne le nom du jeu
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Modifie le nom du jeu
     *
     * @param string|null $name
     * @return void
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * Retourne la description du jeu
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Modifie la description du jeu
     *
     * @param string|null $description
     * @return void
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * Retourne l'image du jeu
     *
     * @return string|null
     */
    public function getPathImg(): ?string
    {
        return $this->pathImg;
    }

    /**
     * Modifie l'image du jeu
     *
     * @param string|null $pathImg
     * @return void
     */
    public function setPathImg(?string $pathImg): void
    {
        $this->pathImg = $pathImg;
    }

    /**
     * Retourne l'état du jeu
     *
     * @return State|null
     */
    public function getState(): ?State
    {
        return $this->state;
    }

    /**
     * Modifie l'état du jeu
     *
     * @param State|null $state
     * @return void
     */
    public function setState(?State $state): void
    {
        $this->state = $state;
    }

    /**
     * Retourne la date de création du jeu
     *
     * @return DateTime|null
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * Modifie la date de création du jeu
     *
     * @param DateTime|null $createdAt
     * @return void
     */
    public function setCreatedAt(?DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Retourne la date de mise à jour du jeu
     *
     * @return DateTime|null
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }
    /**
     * Modifie la date de mise à jour du jeu
     *
     * @param DateTime|null $updatedAt
     * @return void
     */
    public function setUpdatedAt(?DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }




}