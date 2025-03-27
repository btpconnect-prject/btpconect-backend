<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use App\Repository\ProductEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use App\State\ProductProcessorPost as StateProductProcessorPost;
use Symfony\Component\Serializer\Annotation\Groups;


#[ApiResource(
    normalizationContext: ['groups' => ['product::read', 'category::read', 'mediaObject::read']],
    paginationItemsPerPage: 20, // Nombre d'éléments par page
    paginationMaximumItemsPerPage: 100, // Nombre maximum d'éléments par page 
    paginationEnabled: true, // Activer la pagination
    operations: [
        new GetCollection(uriTemplate: "/products", forceEager: false),
        new Get(uriTemplate: "/product/{id}", forceEager: false),
        new Post(uriTemplate: "/product"),
        new Put(
            uriTemplate: "/product/{id}",
            forceEager: false,
            processor: StateProductProcessorPost::class
        ),
        new Delete(
            uriTemplate: "/product/{id}",
            forceEager: false,
            processor: StateProductProcessorPost::class
        )
    ]
)]
#[ORM\Entity(repositoryClass: ProductEntityRepository::class)]
class ProductEntity
{
    use UuidTrait;

    #[ORM\Column(length: 255)]
    #[Groups(["category::read", "product::read"])]
    private ?string $productName = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["category::read", "product::read"])]
    private ?float $currentPrice = null;

    #[ORM\Column(columnDefinition: "TEXT", length: 8000)]
    #[Groups(["category::read", "product::read"])]
    private ?string $coverImage = null;

    #[ORM\ManyToOne(targetEntity: MediaObject::class)]
    #[ORM\JoinColumn(nullable: true)]
    #[ApiProperty(types: ['https://schema.org/image'])]
    #[Groups(["product::read", "mediaObject::read"])]
    public ?MediaObject $image = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["category::read", "product::read"])]
    private ?float $previousPrice = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["category::read", "product::read"])]
    private ?int $rating = null;

    #[ORM\Column]
    #[Groups(["category::read", "product::read"])]
    private ?bool $justIn = null;

    #[ORM\Column]
    #[Groups(["category::read", "product::read"])]
    private ?int $pieces_sold = null;

    #[ORM\ManyToOne(inversedBy: 'products', cascade: ["persist"])]
    #[Groups(["product::read"])]
    #[ORM\JoinColumn(nullable: false)]
    private ?CategorieEntity $category = null;

    #[Groups(["product::read", 'mediaObject::read'])]
    #[ORM\Column(nullable: true)]
    private ?bool $isFeatured = null;

    /**
     * @var Collection<UuidInterface, MediaObject>
     */
    #[ORM\OneToMany(targetEntity: MediaObject::class, mappedBy: 'product', cascade: ["persist", "remove"])]
    #[Groups(["product::read", 'mediaObject::read'])]
    private Collection $shots;

    public function __construct()
    {
        $this->isFeatured = false;
        $this->shots = new ArrayCollection();
    }


    public function getproductName(): ?string
    {
        return $this->productName;
    }

    public function setproductName(string $productName): static
    {
        $this->productName = $productName;

        return $this;
    }

    public function getcurrentPrice(): ?float
    {
        return $this->currentPrice;
    }

    public function setcurrentPrice(?float $currentPrice): static
    {
        $this->currentPrice = $currentPrice;

        return $this;
    }

    public function getCoverImage(): ?string
    {
        return $this->coverImage;
    }

    public function setCoverImage(string $coverImage): static
    {
        $this->coverImage = $coverImage;

        return $this;
    }

    public function getPreviousPrice(): ?float
    {
        return $this->previousPrice;
    }

    public function setPreviousPrice(?float $previousPrice): static
    {
        $this->previousPrice = $previousPrice;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(?int $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    public function isJustIn(): ?bool
    {
        return $this->justIn;
    }

    public function setJustIn(bool $justIn): static
    {
        $this->justIn = $justIn;

        return $this;
    }

    public function getPiecesSold(): ?int
    {
        return $this->pieces_sold;
    }

    public function setPiecesSold(int $pieces_sold): static
    {
        $this->pieces_sold = $pieces_sold;

        return $this;
    }

    public function getCategory(): ?CategorieEntity
    {
        return $this->category;
    }

    public function setCategory(?CategorieEntity $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function isisFeatured(): ?bool
    {
        return $this->isFeatured;
    }

    public function setisFeatured(?bool $isFeatured): static
    {
        $this->isFeatured = $isFeatured;

        return $this;
    }

    public function getImage(): ?MediaObject
    {
        return $this->image;
    }

    public function setImage(?MediaObject $image): static
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return Collection<int, MediaObject>
     */
    public function getShots(): Collection
    {
        return $this->shots;
    }

    public function addShot(MediaObject $shot): static
    {
        if (!$this->shots->contains($shot)) {
            $this->shots->add($shot);
            $shot->setProduct($this);
        }

        return $this;
    }

    public function removeShot(MediaObject $shot): static
    {
        if ($this->shots->removeElement($shot)) {
            // set the owning side to null (unless already changed)
            if ($shot->getProduct() === $this) {
                $shot->setProduct(null);
            }
        }

        return $this;
    }

    public function dissociateMediaBeforeDelete(): void
    {
        // Dissocier les images associées (shots)
        foreach ($this->getShots() as $shot) {
            $this->removeShot($shot);
        }

        // Dissocier l'image principale
        if ($this->getImage() !== null) {
            $this->setImage(null);
        }
    }
}
