<?php
/**
 * @file game.class.php
 * @brief Le fichier contient la déclaration et la définition de la classe Game
 * @author Conchez-Boueytou Robin
 * @date 13/11/2024
 * @version 0.1
 */

namespace ComusParty\Models;

use DateTime;

/**
 * @brief Enumération des états d'un jeu
 *
 * @details Cette énumération définit les trois états possibles d'un jeu:
 *  - Disponible (AVAILABLE)
 *  - Indisponible (UNAVAILABLE)
 *  - En maintenance (MAINTENANCE)
 *
 * @enum GameState
 */
enum GameState
{
    /**
     * @brief L'état AVAILABLE indique que le jeu est disponible pour les utilisateurs.
     */
    case AVAILABLE;

    /**
     * @brief L'état UNAVAILABLE indique que le jeu est actuellement indisponible.
     */
    case UNAVAILABLE;

    /**
     * @brief L'état MAINTENANCE indique que le jeu est en cours de maintenance.
     */
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
     * @var GameState|null
     */
    private ?GameState $state;

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
     * @brief Liste des tags du jeu
     *
     * @var array|null
     */
    private ?array $tags;

    /**
     * @brief Constructeur de la classe Game
     * @param int|null $id Identifiant du jeu
     * @param string|null $name Nom du jeu
     * @param string|null $description Description du jeu
     * @param string|null $pathImg Chemin d'accès à l'image du jeu
     * @param GameState|null $state État du jeu
     * @param DateTime|null $createdAt Date de création du jeu
     * @param DateTime|null $updatedAt Date de mise à jour du jeu
     * @param array|null $tags Liste des tags du jeu
     */
    public function __construct(
        ?int       $id = null,
        ?string    $name = null,
        ?string    $description = null,
        ?string    $pathImg = null,
        ?GameState $state = null,
        ?DateTime  $createdAt = null,
        ?DateTime  $updatedAt = null,
        ?array     $tags = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->pathImg = $pathImg;
        $this->state = $state;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->tags = $tags;
    }

    /**
     * @brief Retourne l'identifiant du jeu
     *
     * @return int|null L'identifiant du jeu
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @brief Modifie l'identifiant du jeu
     *
     * @param int|null $id L'identifiant du jeu
     * @return void
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @brief Retourne le nom du jeu
     *
     * @return string|null Le nom du jeu
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @brief Modifie le nom du jeu
     *
     * @param string|null $name Le nom du jeu
     * @return void
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @brief Retourne la description du jeu
     *
     * @return string|null La description du jeu
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @brief Modifie la description du jeu
     *
     * @param string|null $description La description du jeu
     * @return void
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @brief Retourne le chemin d'accès à l'image du jeu
     *
     * @return string|null Le chemin d'accès à l'image du jeu
     */
    public function getPathImg(): ?string
    {
        return $this->pathImg;
    }

    /**
     * @brief Modifie le chemin d'accès à l'image du jeu
     *
     * @param string|null $pathImg Le chemin d'accès à l'image du jeu
     * @return void
     */
    public function setPathImg(?string $pathImg): void
    {
        $this->pathImg = $pathImg;
    }

    /**
     * @brief Retourne l'état du jeu
     *
     * @return GameState|null L'état du jeu
     */
    public function getState(): ?GameState
    {
        return $this->state;
    }

    /**
     * @brief Modifie l'état du jeu
     *
     * @param GameState|null $state L'état du jeu
     * @return void
     */
    public function setState(?GameState $state): void
    {
        $this->state = $state;
    }

    /**
     * @brief Retourne la date de création du jeu
     *
     * @return DateTime|null La date de création du jeu
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * @brief Modifie la date de création du jeu
     *
     * @param DateTime|null $createdAt La date de création du jeu
     * @return void
     */
    public function setCreatedAt(?DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @brief Retourne la date de mise à jour du jeu
     *
     * @return DateTime|null La date de mise à jour du jeu
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @brief Modifie la date de mise à jour du jeu
     *
     * @param DateTime|null $updatedAt La date de mise à jour du jeu
     * @return void
     */
    public function setUpdatedAt(?DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @brief Retourne la liste des tags du jeu
     *
     * @return array|null La liste des tags du jeu
     */
    public function getTags(): ?array
    {
        return $this->tags;
    }

    /**
     * @brief Modifie la liste des tags du jeu
     *
     * @param array|null $tags La liste des tags du jeu
     * @return void
     */
    public function setTags(?array $tags): void
    {
        $this->tags = $tags;
    }
}