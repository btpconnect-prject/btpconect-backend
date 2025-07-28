<?php

// src/Dto/SearchResult.php

namespace App\Dto;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Controller\SearchController;

use ApiPlatform\Metadata\QueryParameter;


#[ApiResource(
    shortName: 'Search',
    operations: [
        new GetCollection(
            uriTemplate: '/search',
            controller: SearchController::class,
            read: false,
            output: SearchResult::class,
            normalizationContext: ['groups' => ['search']],
            name: 'search_get_collection',
            description: 'Effectue une recherche dans Meilisearch',
            parameters: [
                new QueryParameter(
                    key: 'q',
                    required: true,
                    description: 'Terme de recherche',
                    schema: ['type' => 'string']
                ),
                new QueryParameter(
                    key: 'index',
                    required: true,
                    description: 'Nom de l’index Meilisearch',
                    schema: ['type' => 'string']
                ),
            ],
        )
    ],
    extraProperties: [
        'is_search_result' => true
    ]
)]
class SearchResult
{
    #[Groups(['search'])]
    public array $results;

    #[Groups(['search'])]
    public int $total;

    public function __construct(array $results,  int $total)
    {
        $this->results = $results;
         $this->total = $total;
    }
}
