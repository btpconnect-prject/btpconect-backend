<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\AddressRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    normalizationContext: ['groups' => ['user::read', 'address::read', 'user::read']],
    denormalizationContext: ['groups' => ['user::write', 'address::write']],
    operations: [
        new GetCollection(
            uriTemplate: "/addresses",
            forceEager: false
        ),
        new Get(
            uriTemplate: "/addresses/{id}",
            security: 'is_authenticated()',
        ),
        new Post(
            uriTemplate: "/addresses",
            //security: 'is_authenticated()'
        ),
        new Put(
            uriTemplate: "/addresses/{id}",
            security: 'is_authenticated()'
        ),
        new Delete(
            uriTemplate: "/addresses/{id}",
            forceEager: false,
            security: 'is_authenticated()',
        )
    ]
)]
#[ORM\Entity(repositoryClass: AddressRepository::class)]
#[ApiResource]
class Address
{
    use UuidTrait;

    #[ORM\Column(length: 255)]
    #[Groups(["user::read", 'address::read', 'user::read', 'user::write', 'address::write'])]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    #[Groups(["user::read", 'address::read', 'user::read', 'user::write', 'address::write'])]
    private ?string $region = null;

    #[ORM\Column(length: 255)]
    #[Groups(["user::read", 'address::read', 'user::read', 'user::write', 'address::write'])]
    private ?string $postalCode = null;

    #[ORM\Column(length: 255)]
    #[Groups(["user::read", 'address::read', 'user::read', 'user::write', 'address::write'])]
    private ?string $adresse = null;


    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): static
    {
        $this->region = $region;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): static
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

}
