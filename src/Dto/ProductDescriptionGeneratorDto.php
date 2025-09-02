<?php

// src/Dto/SearchResult.php
namespace App\Dto;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\QueryParameter;
use App\Controller\ProductDescriptionGeneratorController;

#[ApiResource(
    shortName: 'ia',
    operations: [
        new GetCollection(
            uriTemplate: '/generate-ai',
            controller: ProductDescriptionGeneratorController::class,
            read: false,
            output: SearchResult::class,
            normalizationContext: ['groups' => ['search']],
            name: 'ai_get_collection',
            description: 'générer un prompt IA',
            parameters: [
                new QueryParameter(
                    key: 'prompt',
                    required: true,
                    description: 'contenu du prompt',
                    schema: ['type' => 'string']
                ),
                new QueryParameter(
                    key: 'format',
                    required: true,
                    description: 'format de retour',
                    schema: ['type' => 'string']
                ),
            ],
        )
    ],
    extraProperties: [
        'is_search_result' => true
    ]
)]
class ProductDescriptionGeneratorDto
{
    #[Groups(['search'])]
    public array $results;

    public function __construct(array $results)
    {
        $this->results = $results;
    }
}
