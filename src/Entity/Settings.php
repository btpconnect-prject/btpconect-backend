<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\SettingsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SettingsRepository::class)]

#[ApiResource(
    normalizationContext: ['groups' => ['settings::read']],
    operations: [
        new \ApiPlatform\Metadata\Get(),
        new \ApiPlatform\Metadata\Patch(),
        new \ApiPlatform\Metadata\Delete(),
        new \ApiPlatform\Metadata\Put(),
        new \ApiPlatform\Metadata\Post(),
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['config_name' => 'exact'])]
class Settings
{

    use UuidTrait;

    #[ORM\Column(length: 255)]
    #[Groups("settings::read")]
    private ?string $config_name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("settings::read")]
    private ?string $string_value = null;

    #[ORM\Column(nullable: true)]
    #[Groups("settings::read")]
    private ?int $int_value = null;

    #[ORM\Column(nullable: true)]
    #[Groups("settings::read")]
    private ?bool $bool_value = null;

    #[ORM\Column(nullable: true)]
    #[Groups("settings::read")]
    private ?array $json_value = null;



    public function getConfigName(): ?string
    {
        return $this->config_name;
    }

    public function setConfigName(string $config_name): static
    {
        $this->config_name = $config_name;

        return $this;
    }

    public function getStringValue(): ?string
    {
        return $this->string_value;
    }

    public function setStringValue(?string $string_value): static
    {
        $this->string_value = $string_value;

        return $this;
    }

    public function getIntValue(): ?int
    {
        return $this->int_value;
    }

    public function setIntValue(?int $int_value): static
    {
        $this->int_value = $int_value;

        return $this;
    }

    public function isBoolValue(): ?bool
    {
        return $this->bool_value;
    }

    public function setBoolValue(?bool $bool_value): static
    {
        $this->bool_value = $bool_value;

        return $this;
    }

    public function getJsonValue(): ?array
    {
        return $this->json_value;
    }

    public function setJsonValue(?array $json_value): static
    {
        $this->json_value = $json_value;

        return $this;
    }
}
