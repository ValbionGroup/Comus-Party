<?php
// Type enum pour avatar et banniere

enum Type{
    case ProfilePicture;
    case Banner;
}


// Objet représentant un Article
class Article {


    private ?int $id;

    private ?DateTime $createdAt;

    private ?DateTime $updatedAt;
    private ?string $name;

    private ?Type $type;

    private ?string $description;
    private ?int $pricePoint;
    private ?int $priceEuro;
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








    public function getId(): int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getType(): Type
    {
        return $this->type;
    }

    public function setType(?Type $type): void
    {
        $this->type = $type;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getPricePoint(): ?int
    {
        return $this->pricePoint;
    }

    public function setPricePoint(?int $pricePoint): void
    {
        $this->pricePoint = $pricePoint;
    }

    public function getPriceEuro(): ?int
    {
        return $this->priceEuro;
    }

    public function setPriceEuro(?int $priceEuro): void
    {
        $this->priceEuro = $priceEuro;
    }

    public function getPathImg(): ?string
    {
        return $this->pathImg;
    }

    public function setPathImg(?string $pathImg): void
    {
        $this->pathImg = $pathImg;
    }

}
