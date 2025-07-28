<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Metadata\ApiFilter;
use App\Repository\CategorieEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    normalizationContext: ['groups' => ['category::read', "product::read", "search"]],
    operations: [
        new GetCollection(uriTemplate: "/categories",  forceEager: false),
        new Get(uriTemplate: "/categorie/{id}",  forceEager: false),
        new Post(uriTemplate: "/categorie"),
        new Put(uriTemplate: "/categorie/{id}"),
        new Delete(uriTemplate: "/categorie/{id}")
    ]
)]
#[ApiFilter(BooleanFilter::class, properties: ["isSubCategory"])]
#[ORM\Entity(repositoryClass: CategorieEntityRepository::class)]
class CategorieEntity
{

    use UuidTrait;

    #[ORM\Column(length: 255)]
    #[Groups("category::read", "product::read", "search",)]
    #[Assert\NotBlank]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("category::read", "search", "product::read")]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("category::read")]
    private ?string $icon = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Groups("category::read")]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Groups("category::read")]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<UuidInterface, self>
     */
    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'attachSubsCategorie', cascade: ["persist"])]
    #[Groups("category::read")]
    private Collection $subsCategories;

    /**
     * @var Collection<UuidInterface, self>
     */
    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'subsCategories', cascade: ["persist"])]
    private Collection $attachSubsCategorie;

    #[ORM\Column(type: 'json',  nullable: true)]
    #[Groups(["category::read"])]
    private ?array $childCategories = null;

    #[ORM\Column]
    #[Groups("category::read")]
    private ?bool $isFeatured = false;

    #[ORM\Column(nullable: true)]
    #[Groups("category::read")]
    private ?bool $isSubCategory = false;

    /**
     * @var Collection<UuidInterface, ProductEntity>
     */
    #[ORM\OneToMany(targetEntity: ProductEntity::class, mappedBy: 'category', cascade: ["persist"])]
    #[Groups("category::read")]
    private Collection $products;


    public function __construct()
    {
        $this->createdAt           = new \DateTimeImmutable();
        $this->updatedAt           = new \DateTimeImmutable();
        $this->subsCategories      = new ArrayCollection();
        $this->attachSubsCategorie = new ArrayCollection();
        $this->isFeatured          = false;
        $this->products = new ArrayCollection();
    }


    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getSubsCategories(): Collection
    {
        return $this->subsCategories;
    }

    public function addSubsCategory(self $subsCategory): static
    {
        if (!$this->subsCategories->contains($subsCategory)) {
            $this->subsCategories->add($subsCategory);
        }

        return $this;
    }

    public function removeSubsCategory(self $subsCategory): static
    {
        $this->subsCategories->removeElement($subsCategory);

        return $this;
    }

    /**
     * @return Collection<UuidInterface, self>
     */
    public function getAttachSubsCategorie(): Collection
    {
        return $this->attachSubsCategorie;
    }

    public function addAttachSubsCategorie(self $attachSubsCategorie): static
    {
        if (!$this->attachSubsCategorie->contains($attachSubsCategorie)) {
            $this->attachSubsCategorie->add($attachSubsCategorie);
            $attachSubsCategorie->addSubsCategory($this);
        }

        return $this;
    }

    public function removeAttachSubsCategorie(self $attachSubsCategorie): static
    {
        if ($this->attachSubsCategorie->removeElement($attachSubsCategorie)) {
            $attachSubsCategorie->removeSubsCategory($this);
        }

        return $this;
    }

    public function isFeatured(): ?bool
    {
        return $this->isFeatured;
    }

    public function setIsFeatured(bool $isFeatured): static
    {
        $this->isFeatured = $isFeatured;

        return $this;
    }

    public function getIsSubCategory(): ?bool
    {
        return $this->isSubCategory;
    }

    public function setIsSubCategory(?bool $isSubCategory): static
    {
        $this->isSubCategory = $isSubCategory;

        return $this;
    }

    /**
     * @return Collection<int, ProductEntity>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(ProductEntity $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setCategory($this);
        }

        return $this;
    }

    public function removeProduct(ProductEntity $product): static
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getCategory() === $this) {
                $product->setCategory(null);
            }
        }

        return $this;
    }

    /*
     * @see UserInterface
     */
    public function getChildCategories(): ?array
    {
        return array_unique($this->childCategories ?? []);
    }

    public function setChildCategories(array $childCategories): static
    {
        $this->childCategories = $childCategories;
        return $this;
    }
}
