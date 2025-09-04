<?php

// src/Dto/SearchResult.php
namespace App\Dto;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\QueryParameter;
use App\Controller\ImagesGeneratorController;

#[ApiResource(
    shortName: 'dynaspark',
    operations: [
        new GetCollection(
            uriTemplate: '/generate',
            controller: ImagesGeneratorController::class,
            read: false,
            paginationEnabled:false,
            paginationClientEnabled:false,
            output: SearchResult::class,
            normalizationContext: ['groups' => ['search']],
            name: 'dyna_get_collection',
            description: 'générer un prompt IA',
            parameters: [
                new QueryParameter(
                    key: 'prompt',
                    required: true,
                    description: 'contenu du prompt',
                    schema: ['type' => 'string']
                ),
            ],
        )
    ],
    extraProperties: [
        'is_search_result' => true
    ]
)]
class ImagesGeneratorDto
{
    #[Groups(['search'])]
    public array $results;

    public function __construct(array $results)
    {
        $this->results = $results;
    }
}
