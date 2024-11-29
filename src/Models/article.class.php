<?php
/**
 * @file    article.class.php
 * @author  Mathis RIVRAIS--NOWAKOWSKI
 * @brief   Le fichier contient la déclaration & définition de la classe Article.
 * @date    13/11/2024
 * @version 0.1
 */

namespace ComusParty\Models;

use DateTime;

/**
 * @brief Les 2 types possible pour les articles
 *
 * @details Les types d'articles sont les suivants :
 *  - ProfilePicture : Image de profil
 *  - Banner : Bannière
 *
 * @enum ArticleType
 */
enum ArticleType {
    /**
     * @brief Image de profil
     */
    case ProfilePicture;

    /**
     * @brief Bannière
     */
    case Banner;
}



/**
 * @brief Classe Article
 * @details La classe Article représente un article de l'application
 */
class Article {


    /**
     * @brief L'id de l'article - identifiant unique
     *
     * @var int|null
     *
     */
    private ?int $id;

    /**
     * @brief La date de création de l'article
     *
     * @var DateTime|null
     *
     */
    private ?DateTime $createdAt;

    /**
     * @brief La date d'update de l'article
     *
     * @var DateTime|null
     *
     */
    private ?DateTime $updatedAt;
    /**
     * @brief Le nom de l'article
     *
     * @var string|null
     *
     */
    private ?string $name;

    /**
     * @brief Le type de l'article
     *
     * @var ArticleType|null
     *
     */
    private ?ArticleType $type;

    /**
     * @brief La description de l'article
     *
     * @var string|null
     */
    private ?string $description;
    /**
     * @brief Le prix de l'article
     *
     * @var int|null
     */
    private ?int $pricePoint;
    /**
     * @brief Le prix en euro de l'article
     *
     * @var int|null
     */
    private ?int $priceEuro;
    /**
     * @brief Le chemin d'accès de l'image
     *
     * @var string|null
     *
     */
    private ?string $filePath;

    /**
     * @brief Le constructeur de la classe Article
     * @param int|null $id L'id de l'article
     * @param DateTime|null $createdAt La date de création de l'article
     * @param DateTime|null $updatedAt La date de mis-à-jour de l'article
     * @param string|null $name Le nom de l'article
     * @param ArticleType|null $type Le type de l'article
     * @param string|null $description La description de l'article
     * @param int|null $pricePoint Le prix en point de l'article
     * @param int|null $priceEuro Le prix en euro de l'article
     * @param string|null $filePath Le chemin de l'image de l'article
     */
    public function __construct(?int $id = null, ?DateTime $createdAt = null, ?DateTime $updatedAt = null, ?string $name = null, ?ArticleType $type = null, ?string $description = null , ?int $pricePoint = null, ?int $priceEuro = null, ?string $filePath = null)
    {
        $this->id = $id;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->name = $name;
        $this->type = $type;
        $this->description = $description;
        $this->pricePoint = $pricePoint;
        $this->priceEuro = $priceEuro;
        $this->filePath = $filePath;
    }


    /**
     * @brief Retourne l'id de l'article
     *
     * @return int|null L'id de l'article est retourné
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @brief Modifie l'id de l'article
     *
     * @param int|null $id Le nouveau id de l'article
     * @return void
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @brief Retourne le nom de l'article
     *
     * @return string|null Le nom de l'article retourné
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @brief Modifie le nom de l'article
     *
     * @param string|null $name Le nouveau nom de l'article
     * @return void
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @brief Retourne le type de l'article
     *
     * @return ArticleType Le type de l'article qui est rétourné
     */
    public function getType(): ArticleType
    {
        return $this->type;
    }

    /**
     * @brief Modifie le type de l'article
     *
     * @param ArticleType|null $type Le nouveau type de l'article
     * @return void
     */
    public function setType(?ArticleType $type): void
    {
        $this->type = $type;
    }

    /**
     * @brief Retourne la description de l'article
     *
     * @return string|null La description de l'article retourné
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @brief Modifie la description de l'article
     *
     * @param string|null $description La nouvelle description de l'article
     * @return void
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @brief Retourne la date de création de l'article
     *
     * @return DateTime|null La date de création de l'article qui est retournée
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * @brief Modifie la date de création de l'article
     *
     * @param DateTime|null $createdAt La nouvelle date de création de l'article
     * @return void
     */
    public function setCreatedAt(?DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @brief Retourne la date de mis-à-jour de l'article
     *
     * @return DateTime|null La date de mis-à-jour de l'article qui est retournée
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @brief Modifie la date de mis-à-jour de l'article
     *
     * @param DateTime|null $updatedAt La nouvelle date de mis-à-jour de l'article
     * @return void
     */
    public function setUpdatedAt(?DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @brief Retourne le prix en point de l'article
     *
     * @return int|null Le prix en point de l'article qui est retourné
     */
    public function getPricePoint(): ?int
    {
        return $this->pricePoint;
    }

    /**
     * @brief Modifie le prix en point de l'article
     *
     * @param int|null $pricePoint Le nouveau prix en point de l'article
     * @return void
     */
    public function setPricePoint(?int $pricePoint): void
    {
        $this->pricePoint = $pricePoint;
    }

    /**
     * @brief Retourne le prix en euro de l'article
     *
     * @return int|null Le prix en euro de l'article qui est retourné
     */
    public function getPriceEuro(): ?int
    {
        return $this->priceEuro;
    }

    /**
     * @brief Modifie le prix en euro de l'article
     *
     * @param int|null $priceEuro Le nouveau prix de l'article
     * @return void
     */
    public function setPriceEuro(?int $priceEuro): void
    {
        $this->priceEuro = $priceEuro;
    }

    /**
     * @brief Retourne le chemin de l'image de l'article
     *
     * @return string|null Le chemin de l'article qui est retourné
     */
    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    /**
     * @brief Modifie le chemin de l'article
     *
     * @param string|null $filePath Le nouveau chemin de l'article
     * @return void
     */
    public function setFilePath(?string $filePath): void
    {
        $this->filePath = $filePath;
    }

}
