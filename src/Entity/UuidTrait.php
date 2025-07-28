<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;


trait UuidTrait
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[Groups([ "search", "category::read", "product::read", "mediaObject::read", "user::read", "achievement::read", "order::read", "address::read", "order::write", "product::write"])]
    private ?UuidInterface $id = null;
    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    #[ORM\PrePersist]
    public function initializeUuid(): void
    {
        if ($this->id === null) {
            $this->id = \Ramsey\Uuid\Uuid::uuid4();
        }
    }
}
