<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;
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
use Symfony\Component\Serializer\Annotation\MaxDepth;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\Patch;
use App\Controller\ProductBySlugController;
use Gedmo\Mapping\Annotation as Gedmo;
use ApiPlatform\Metadata\Link;
use Doctrine\DBAL\Types\Types;

#[ApiResource(
    normalizationContext: ['groups' => ['product::read', 'category::read', 'mediaObject::read', "order::read", "search"]],
    operations: [
        new GetCollection(
            uriTemplate: "/products",
            forceEager: false,
        ),
        new Get(uriTemplate: "/product/{id}", forceEager: false),
        new Get(
            uriTemplate: "/product/slug/{slug}",
            uriVariables: [
                'slug' => new Link(
                    fromClass: ProductEntity::class,
                    identifiers: ['slug'],
                    fromProperty: 'slug',
                    extraProperties: [
                        'openapi_context' => [
                            'description' => 'Slug SEO unique du produit (ex: chaise-en-bois)',
                            'example' => 'chaise-en-bois'
                        ]
                    ]
                )
            ],
            read: false, // API Platform utilise automatiquement findOneBySlug(), si false alors il faut rajouter un custom controller
            controller: ProductBySlugController::class,
            normalizationContext: ['groups' => ['product::read', 'category::read'], 'max_depth' => true],  // <-- ajoute ça]
        ),
        new Post(
            uriTemplate: "/product",
        ),
        new Put(
            uriTemplate: "/product/{id}",
            forceEager: false,
            processor: StateProductProcessorPost::class
        ),
        new Delete(
            uriTemplate: "/product/{id}",
            forceEager: false,
            processor: StateProductProcessorPost::class
        ),
        new Patch(uriTemplate: "/product/{id}"),
    ]
)]
#[ORM\Entity(repositoryClass: ProductEntityRepository::class)]
#[ApiFilter(SearchFilter::class, properties: ['isFeatured' => 'partial'])]
class ProductEntity
{

    use UuidTrait;

    #[ORM\Column(length: 255)]
    #[Groups(["category::read", "product::read", "order::read", "search"])]
    private ?string $productName = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["category::read", "product::read", "order::read", "search"])]
    private ?float $currentPrice = null;

    #[ORM\Column(columnDefinition: "TEXT", length: 8000)]
    #[Groups(["category::read", "product::read", "order::read"])]
    private ?string $coverImage = null;

    #[ORM\ManyToOne(targetEntity: MediaObject::class)]
    #[ORM\JoinColumn(nullable: true)]
    #[ApiProperty(types: ['https://schema.org/image'])]
    #[MaxDepth(1)] // Limite la profondeur de sérialisation à 1
    #[Groups(["product::read", "mediaObject::read", "order::read", "category::read", "search"])]
    public ?MediaObject $image = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["category::read", "product::read", "order::read", "search"])]
    private ?float $previousPrice = null;


    #[ORM\Column(nullable: true, type: "text")]
    #[Groups(["category::read", "product::read", "order::read", "search"])]
    private $description;

    #[ORM\Column(nullable: true)]
    #[Groups(["category::read", "product::read", "order::read"])]
    private ?int $rating = null;

    #[ORM\Column]
    #[Groups(["category::read", "product::read", "order::read"])]
    private ?bool $justIn = null;

    #[ORM\Column]
    #[Groups(["category::read", "product::read", "order::read"])]
    private ?int $pieces_sold = null;

    #[ORM\ManyToOne(inversedBy: 'products', cascade: ["persist"])]
    #[Groups(["product::read", "search", "category::read"])]
    #[ORM\JoinColumn(nullable: false)]
    #[MaxDepth(1)] // Limite la profondeur de sérialisation à 1
    private ?CategorieEntity $category = null;

    #[Groups(["order::read", "product::read", 'mediaObject::read', "search"])]
    #[ApiFilter(SearchFilter::class, strategy: 'partial')]
    #[ORM\Column(nullable: true)]
    private ?bool $isFeatured = null;

    #[Groups(["order::read", "product::read", 'mediaObject::read', "search"])]
    #[ApiFilter(SearchFilter::class, strategy: 'partial')]
    #[ORM\Column(nullable: true)]
    private ?bool $isVerified = null;

    #[Gedmo\Slug(fields: ['productName'])]
    #[ApiProperty(readable: true, writable: false)]
    #[Groups(["product::read", "category::read", "search"])]
    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private $slug;
    /**
     * Field to track the timestamp for the last change made to this article. 
     */
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Gedmo\Timestampable]
    #[Groups(["product::read", "category::read", "search"])]
    public ?\DateTimeImmutable $updatedAt = null;
    /**
     * Field to track the timestamp for the last change made to this article. 
     */
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Groups(["product::read", "category::read", "search"])]
    #[Gedmo\Timestampable]
    public ?\DateTimeImmutable $createdAt = null;
    /**
     * @var Collection<UuidInterface, MediaObject>
     */
    #[ORM\OneToMany(targetEntity: MediaObject::class, mappedBy: 'product', cascade: ["persist", "remove"])]
    #[MaxDepth(1)] // Limite la profondeur de sérialisation à 1
    #[Groups(["product::read", 'mediaObject::read', "order::read"])]
    private Collection $shots;

    #[ORM\Column(length: 255, nullable: true, type: "text")]
    #[Groups(["category::read", "product::read", "order::read", "search"])]
    private ?string $details = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["category::read", "product::read", "order::read", "search"])]
    private ?string $deliveryDetails = null;

    #[ORM\Column(type: 'json',  nullable: true)]
    #[Groups(["category::read", "product::read", "order::read", "search"])]
    private ?array $productCaractors = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Promotion::class, cascade: ['persist', 'remove'])]
    #[Groups(['product::read'])]
    private Collection $promotions;


    public function __construct()
    {
        $this->isFeatured = false;
        $this->isVerified     = false;
        $this->productCaractors = [];
        $this->details = "";
        $this->shots = new ArrayCollection();
        $this->promotions = new ArrayCollection();
        $this->setUpdatedAt(new \DateTimeImmutable());
        $this->setCreatedAt(new \DateTimeImmutable());
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getProductName(): ?string
    {
        return $this->productName;
    }

    public function setProductName(string $productName): static
    {
        $this->productName = $productName;

        return $this;
    }

    public function getCurrentPrice(): ?float
    {
        return $this->currentPrice;
    }

    public function setCurrentPrice(?float $currentPrice): static
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


    public function isVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(?bool $isFeatured): static
    {
        $this->isFeatured = $isFeatured;

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


    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(?string $details): static
    {
        $this->details = $details;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getDeliveryDetails(): ?string
    {
        return $this->deliveryDetails;
    }

    public function setDeliveryDetails(?string $deliveryDetails): static
    {
        $this->deliveryDetails = $deliveryDetails;

        return $this;
    }

    /*
     * @see UserInterface
     */
    public function getProductCaractors(): ?array
    {
        return $this->productCaractors;
    }

    public function setProductCaractors(array $productCaractors): static
    {
        $this->productCaractors = $productCaractors;
        return $this;
    }


    #[Groups(['product::read'])]
    public function hasActivePromotion(): bool
    {
        $now = new \DateTime();
        foreach ($this->getPromotions() as $promo) {
            if ($promo->getStartDate() <= $now && $promo->getEndDate() >= $now) {
                return true;
            }
        }
        return false;
    }

    #[Groups(['product::read'])]
    public function isInStock(): bool
    {
        return $this->pieces_sold > 0;
    }

    #[Groups(['product::read'])]
    public function isNew(): bool
    {
        if ($this->createdAt === null) {
            return false;
        }
        $now = new \DateTimeImmutable();
        $interval = $now->diff($this->createdAt);
        return ($interval->days === 0 && $interval->h < 1);
    }

    #[Groups(['product::read'])]
    public function getEffectivePrice(): ?float
    {
        $now = new \DateTime();

        foreach ($this->getPromotions() as $promotion) {
            if ($promotion->getStartDate() <= $now && $promotion->getEndDate() >= $now) {
                return $this->currentPrice * (1 - $promotion->getDiscountRate());
            }
        }

        return $this->currentPrice;
    }

    public function getPromotions(): Collection
    {
        return $this->promotions;
    }

    public function addPromotion(Promotion $promotion): static
    {
        if (!$this->promotions->contains($promotion)) {
            $this->promotions->add($promotion);
            $promotion->setProduct($this);
        }

        return $this;
    }

    public function removePromotion(Promotion $promotion): static
    {
        if ($this->promotions->removeElement($promotion)) {
            if ($promotion->getProduct() === $this) {
                $promotion->setProduct(null);
            }
        }

        return $this;
    }
}
