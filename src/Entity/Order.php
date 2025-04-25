<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\State\ConfirmOrderProcessor;
use App\State\OrderProcessor;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;

#[ApiResource(
    normalizationContext: ['groups' => ['user::read', 'address::read', 'order::read', "product::read"]],
    //denormalizationContext: ['groups' => ['user::write', 'address::write', "order::write"]],
    operations: [
        new GetCollection(
            uriTemplate: "/orders",
            forceEager: false,
        ),
        new Get(
            uriTemplate: "/order/{id}",
            forceEager: false,
            //security: 'is_authenticated()'
        ),
        new Post(
            uriTemplate: "/order/sendConfirmation/{id}",
            processor: ConfirmOrderProcessor::class,
            //status: HttpFoundationResponse::HTTP_CREATED,
            read: false, // tu veux lire l'entité avant de la modifier
            write: false,  // empêche la désérialisation des données envoyées dans le body
            forceEager: false,
            input: false, // ← AJOUTER ICI
        ),
        new Post(
            uriTemplate: "/order",
            processor: OrderProcessor::class,
        ),
        new Put(
            uriTemplate: "/order/{id}",
            security: 'is_authenticated()'
        ),
        new Delete(
            uriTemplate: "/order/{id}",
            forceEager: false,
            security: 'is_authenticated()',
        ),
    ]
)]

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    use UuidTrait;

    #[ORM\ManyToOne(inversedBy: 'userOrders', cascade: ['persist', 'remove'])] // Cascade persist and remove
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(["user::read", 'user::write', "order::read", "order::write"])]
    #[ApiProperty(
        readableLink: true,
        writableLink: true // <-- C'est ça qui fait que seuls les IDs sont attendus à l'écriture
    )]
    private ?UserEntity $customer = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $code = null;

    #[ORM\Column(type: 'json')]
    #[Groups(["user::read", 'user::write', "order::read", "order::write"])]
    private ?array $cart = null;

    /**
     * @var Collection<UuidInterface, ProductEntity>
     */
    #[ORM\ManyToMany(targetEntity: ProductEntity::class)]
    #[Groups(["user::read", 'user::write', "order::read",   "product::read"])]
    private Collection $products;

    #[ORM\Column(type: Types::DATE_IMMUTABLE,  nullable: true)]
    #[Groups("order::read")]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups("order::read")]
    private ?\DateTimeImmutable $updatedAt = null;


    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->createdAt           = new \DateTimeImmutable();
        $this->updatedAt           = new \DateTimeImmutable();
        $this->cart = [];
    }



    /**
     * @see UserInterface
     */
    public function getCart(): ?array
    {
        return $this->cart;
    }

    public function setCart(array $cart): static
    {
        $this->cart = $cart;
        return $this;
    }

    public function getCustomer(): ?UserEntity
    {
        return $this->customer;
    }

    public function setCustomer(?UserEntity $customer): static
    {
        $this->customer = $customer;

        return $this;
    }


    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): static
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection<UuidInterface, ProductEntity>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(ProductEntity $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
        }

        return $this;
    }

    public function removeProduct(ProductEntity $product): static
    {
        $this->products->removeElement($product);

        return $this;
    }
}
