<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\NotificationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: NotificationRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['notification::read']],
    denormalizationContext: ['groups' => ['notification::write']]
)]
class Notification
{
    use UuidTrait;

    #[ORM\Column(length: 255)]
    #[Groups(["notification::read", "notification::write"])]
    private ?string $value = null;

    #[ORM\ManyToOne(inversedBy: 'userNotifications')]
    #[Groups(["notification::read", "notification::write"])]
    private ?UserEntity $userNotification = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isActive = null;

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getUserNotification(): ?UserEntity
    {
        return $this->userNotification;
    }

    public function setUserNotification(?UserEntity $userNotification): static
    {
        $this->userNotification = $userNotification;

        return $this;
    }
}
