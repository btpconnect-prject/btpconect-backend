<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PromotionRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\ApiResource;
use App\Entity\ProductEntity;

#[ORM\Entity(repositoryClass: PromotionRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['promotion::read']],
    denormalizationContext: ['groups' => ['promotion::write']]
)]
class Promotion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['promotion::read'])]
    private ?int $id = null;

    #[ORM\Column(type: 'float')]
    #[Groups(['promotion::read', 'promotion::write', 'product::read'])]
    private float $discountRate;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['promotion::read', 'promotion::write'])]
    private \DateTimeInterface $startDate;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['promotion::read', 'promotion::write'])]
    private \DateTimeInterface $endDate;

    #[ORM\ManyToOne(targetEntity: ProductEntity::class, inversedBy: 'promotions')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['promotion::write'])]
    private ?ProductEntity $product = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDiscountRate(): float
    {
        return $this->discountRate;
    }

    public function setDiscountRate(float $discountRate): self
    {
        $this->discountRate = $discountRate;

        return $this;
    }

    public function getStartDate(): \DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): \DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getProduct(): ?ProductEntity
    {
        return $this->product;
    }

    public function setProduct(?ProductEntity $product): self
    {
        $this->product = $product;

        return $this;
    }
}
