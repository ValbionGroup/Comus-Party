<?php


/**
 *
 */
enum Type{
    case ProfilePicture;
    case Banner;
}



/**
 *
 */
class Article {


    /**
     * @var int|null
     */
    private ?int $id;

    /**
     * @var DateTime|null
     */
    private ?DateTime $createdAt;

    /**
     * @var DateTime|null
     */
    private ?DateTime $updatedAt;
    /**
     * @var string|null
     */
    private ?string $name;

    /**
     * @var Type|null
     */
    private ?Type $type;

    /**
     * @var string|null
     */
    private ?string $description;
    /**
     * @var int|null
     */
    private ?int $pricePoint;
    /**
     * @var int|null
     */
    private ?int $priceEuro;
    /**
     * @var string|null
     */
    private ?string $pathImg;

    /**
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
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return void
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return void
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return Type
     */
    public function getType(): Type
    {
        return $this->type;
    }

    /**
     * @param Type|null $type
     * @return void
     */
    public function setType(?Type $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return void
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime|null $createdAt
     * @return void
     */
    public function setCreatedAt(?DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTime|null
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime|null $updatedAt
     * @return void
     */
    public function setUpdatedAt(?DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return int|null
     */
    public function getPricePoint(): ?int
    {
        return $this->pricePoint;
    }

    /**
     * @param int|null $pricePoint
     * @return void
     */
    public function setPricePoint(?int $pricePoint): void
    {
        $this->pricePoint = $pricePoint;
    }

    /**
     * @return int|null
     */
    public function getPriceEuro(): ?int
    {
        return $this->priceEuro;
    }

    /**
     * @param int|null $priceEuro
     * @return void
     */
    public function setPriceEuro(?int $priceEuro): void
    {
        $this->priceEuro = $priceEuro;
    }

    /**
     * @return string|null
     */
    public function getPathImg(): ?string
    {
        return $this->pathImg;
    }

    /**
     * @param string|null $pathImg
     * @return void
     */
    public function setPathImg(?string $pathImg): void
    {
        $this->pathImg = $pathImg;
    }

}
