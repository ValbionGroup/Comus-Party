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
    private ?int $price_point;
    private ?int $price_euro;

    /**
     * @param int $id
     * @param string $name
     * @param Type $type
     * @param string|null $description
     * @param int|null $price_point
     * @param int|null $price_euro
     */


    public function __construct(?int $id = null, ?DateTime $createdAt = null, ?DateTime $updatedAt = null, ?string $name = null, ?Type $type = null, ?string $description = null, ?int $price_point = null, ?int $price_euro=null)
    {
        $this->id = $id;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->name = $name;
        $this->type = $type;
        $this->description = $description;
        $this->price_point = $price_point;
        $this->price_euro = $price_euro;
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
        return $this->price_point;
    }

    public function setPricePoint(?int $price_point): void
    {
        $this->price_point = $price_point;
    }

    public function getPriceEuro(): ?int
    {
        return $this->price_euro;
    }

    public function setPriceEuro(?int $price_euro): void
    {
        $this->price_euro = $price_euro;
    }


}
