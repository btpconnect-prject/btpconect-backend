<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\WorkSpaceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: WorkSpaceEntityRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(uriTemplate: '/workspace'),
        new Get(uriTemplate: '/workspace/{id}'),
        new Post(uriTemplate: '/workspace'),
        new Put(uriTemplate: '/workspace/{id}'),
    ]
)]
class WorkSpaceEntity
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?UuidInterface $id;

    /**
     * @var Collection<UuidInterface, UserEntity>
     */
    #[ORM\ManyToMany(targetEntity: UserEntity::class, mappedBy: 'workSpaces', cascade:["persist"])]
    private ?Collection $users;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $bgImage = null;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    /**
     * @return Collection<int, UserEntity>
     */
    public function getUsers(): ?Collection
    {
        return $this->users;
    }

    public function addUser(UserEntity $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addWorkSpace($this);
        }

        return $this;
    }

    public function removeUser(UserEntity $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeWorkSpace($this);
        }

        return $this;
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

    public function getBgImage(): ?string
    {
        return $this->bgImage;
    }

    public function setBgImage(?string $bgImage): static
    {
        $this->bgImage = $bgImage;

        return $this;
    }
}
