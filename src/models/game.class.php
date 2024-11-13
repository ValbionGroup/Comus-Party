<?php
/**
 * @file game.class.php
 * @brief Le fichier contient la déclaration et la définition de la classe Game
 * @author Conchez-Boueytou Robin
 * @date 13/11/2024
 * @version 0.1
 */

/**
 * @brief Enumération des états d'un jeu (AVAILABLE, UNVAILABLE, MAINTENANCE)
 *
 * @var State
 */
enum State {
    case AVAILABLE;
    case UNVAILABLE;
    case MAINTENANCE;
}

/**
 * @brief Classe Game
 * @details La classe Game permet de représenter un jeu avec ses attributs et ses méthodes
 */
class Game
{
    /**
     * @brief Identifiant du jeu
     *
     * @var int|null
     */
    private ?int $id;

    /**
     * @brief Nom du jeu
     *
     * @var string|null
     */
    private ?string $name;

    /**
     * @brief Description du jeu
     *
     * @var string|null
     */
    private ?string $description;

    /**
     * @brief Chemin d'accès à l'image du jeu
     *
     * @var string|null
     */
    private ?string $pathImg;

    /**
     * @brief État du jeu
     *
     * @var State|null
     */
    private ?State $state;

    /**
     * @brief Date de création du jeu
     *
     * @var DateTime|null
     */
    private ?DateTime $createdAt;

    /**
     * @brief Date de mise à jour du jeu
     *
     * @var DateTime|null
     */
    private ?DateTime $updatedAt;

    /**
     * @brief Constructeur de la classe Game
     * @param int|null $id identifiant du jeu
     * @param string|null $name nom du jeu
     * @param string|null $description description du jeu
     * @param string|null $pathImg chemin d'accès à l'image du jeu
     * @param State|null $state état du jeu
     * @param DateTime|null $createdAt date de création du jeu
     * @param DateTime|null $updatedAt date de mise à jour du jeu
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
     * @brief Retourne l'identifiant du jeu
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @brief Modifie l'identifiant du jeu
     *
     * @param int|null $id
     * @return void
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @brief Retourne le nom du jeu
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @brief Modifie le nom du jeu
     *
     * @param string|null $name
     * @return void
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @brief Retourne la description du jeu
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @brief Modifie la description du jeu
     *
     * @param string|null $description
     * @return void
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @brief Retourne le chemin d'accès à l'image du jeu
     *
     * @return string|null
     */
    public function getPathImg(): ?string
    {
        return $this->pathImg;
    }

    /**
     * @brief Modifie le chemin d'accès à l'image du jeu
     *
     * @param string|null $pathImg
     * @return void
     */
    public function setPathImg(?string $pathImg): void
    {
        $this->pathImg = $pathImg;
    }

    /**
     * @brief Retourne l'état du jeu
     *
     * @return State|null
     */
    public function getState(): ?State
    {
        return $this->state;
    }

    /**
     * @brief Modifie l'état du jeu
     *
     * @param State|null $state
     * @return void
     */
    public function setState(?State $state): void
    {
        $this->state = $state;
    }

    /**
     * @brief Retourne la date de création du jeu
     *
     * @return DateTime|null
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * @brieg Modifie la date de création du jeu
     *
     * @param DateTime|null $createdAt
     * @return void
     */
    public function setCreatedAt(?DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @brief Retourne la date de mise à jour du jeu
     *
     * @return DateTime|null
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }
    /**
     * @brief Modifie la date de mise à jour du jeu
     *
     * @param DateTime|null $updatedAt
     * @return void
     */
    public function setUpdatedAt(?DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}