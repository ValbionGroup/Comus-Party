<?php


/**
 * Les 2 types possible pour les articles
 */
enum Type{
    case ProfilePicture;
    case Banner;
}



/**
 * L'objet Article représentant un article disponible dans la boutique
 */
class Article {


    /**
     * L'id de l'article
     *
     * @var int|null
     *
     */
    private ?int $id;

    /**
     * @var DateTime|null
     * La date de création de l'article
     */
    private ?DateTime $createdAt;

    /**
     * La date d'update de l'article
     *
     * @var DateTime|null
     *
     */
    private ?DateTime $updatedAt;
    /**
     * Le nom de l'article
     *
     * @var string|null
     *
     */
    private ?string $name;

    /**
     * Le type de l'article
     *
     * @var Type|null
     *
     */
    private ?Type $type;

    /**
     * La description de l'article
     *
     * @var string|null
     */
    private ?string $description;
    /**
     * Le prix de l'article
     *
     * @var int|null
     */
    private ?int $pricePoint;
    /**
     * Le prix en euro de l'article
     *
     * @var int|null
     */
    private ?int $priceEuro;
    /**
     * Le chemin d'accès de l'image
     *
     * @var string|null
     *
     */
    private ?string $pathImg;

    /**
     * But : Le constructeur de Article
     * @param int|null $id
     * @param DateTime|null $createdAt
     * @param DateTime|null $updatedAt
     * @param string|null $name
     * @param Type|null $type
     * @param string|null $description
     * @param int|null $price_point
     * @param int|null $price_euro
     * @param string|null $path_img
     */
    public function __construct(?int $id = null, ?DateTime $createdAt = null, ?DateTime $updatedAt = null, ?string $name = null, ?Type $type = null, ?string $description = null ,  ?int $pricePoint = null, ?int $priceEuro = null, ?string $pathImg = null)
    {
        $this->id = $id;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->name = $name;
        $this->type = $type;
        $this->description = $description;
        $this->pricePoint = $pricePoint;
        $this->priceEuro = $priceEuro;
        $this->pathImg = $pathImg;
    }


    /**
     * But : Permet d'obtenir l'id de l'article
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * But : Permet de set l'id de l'Article
     *
     * @param int|null $id
     * @return void
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * But : Permet d'obtenir le nom de l'article
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * But : Permet de set le nom de l'article
     *
     * @param string|null $name
     * @return void
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * But : Permet d'obtenir le type de l'article
     *
     * @return Type
     */
    public function getType(): Type
    {
        return $this->type;
    }

    /**
     * But : Permet de set le type de l'article
     *
     * @param Type|null $type
     * @return void
     */
    public function setType(?Type $type): void
    {
        $this->type = $type;
    }

    /**
     * But : Permet d'obtenir la description de l'article
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * But : Permet de set la description
     *
     * @param string|null $description
     * @return void
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * But : Permet d'obtenir la date de création de l'article
     *
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * But : Permet de set la date de création
     *
     * @param DateTime|null $createdAt
     * @return void
     */
    public function setCreatedAt(?DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * But : Permet d'obtenir la date de maj de l'article
     *
     * @return DateTime|null
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
     * But : Permet de set la date de maj de l'article
     *
     * @param DateTime|null $updatedAt
     * @return void
     */
    public function setUpdatedAt(?DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * But : Permet d'obtenir le prix en Comus de l'article
     *
     * @return int|null
     */
    public function getPricePoint(): ?int
    {
        return $this->pricePoint;
    }

    /**
     * But : Permet de set en point l'article
     *
     * @param int|null $pricePoint
     * @return void
     */
    public function setPricePoint(?int $pricePoint): void
    {
        $this->pricePoint = $pricePoint;
    }

    /**
     * But : Permet d'obtenir le prix en euro de l'article
     *
     * @return int|null
     */
    public function getPriceEuro(): ?int
    {
        return $this->priceEuro;
    }

    /**
     * But : Permet de set le prix en euro de l'article
     *
     * @param int|null $priceEuro
     * @return void
     */
    public function setPriceEuro(?int $priceEuro): void
    {
        $this->priceEuro = $priceEuro;
    }

    /**
     * But : Permet d'obtenir le chemin de l'image
     *
     * @return string|null
     */
    public function getPathImg(): ?string
    {
        return $this->pathImg;
    }

    /**
     * But : Permet de set le chemin de l'img
     *
     * @param string|null $pathImg
     * @return void
     */
    public function setPathImg(?string $pathImg): void
    {
        $this->pathImg = $pathImg;
    }

}
