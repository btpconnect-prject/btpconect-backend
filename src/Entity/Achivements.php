<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use App\Repository\AchivementsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    normalizationContext: ['groups' => ['achievement::read']],
    paginationItemsPerPage: 20, // Nombre d'éléments par page
    paginationMaximumItemsPerPage: 100, // Nombre maximum d'éléments par page 
    paginationEnabled: true, // Activer la pagination
    operations: [
        new GetCollection(uriTemplate: "/achievements", forceEager: false),
        new Get(uriTemplate: "/achievement/{id}", forceEager: false),
        new Post(uriTemplate: "/achievement"),
        new Put(
            uriTemplate: "/achievement/{id}",
            forceEager: false
        ),
        new Delete(
            uriTemplate: "/achievement/{id}",
            forceEager: false,
        )
    ]
)]
#[ORM\Entity(repositoryClass: AchivementsRepository::class)]
class Achivements
{

    use UuidTrait;

    #[ORM\Column(length: 255)]
    #[Groups(["achievement::read"])]
    private ?string $videosUrl = null;


    public function getVideosUrl(): ?string
    {
        return $this->videosUrl;
    }

    public function setVideosUrl(string $videosUrl): static
    {
        $this->videosUrl = $videosUrl;

        return $this;
    }
}
