<?php

// src/Controller/SearchController.php

namespace App\Controller;

use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Dto\SearchResult;
use Meilisearch\Client;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class SearchController
{
    private Client $meilisearch;

    public function __construct(string $meilisearchUrl, string $meilisearchKey)
    {
        $this->meilisearch = new Client($meilisearchUrl, $meilisearchKey);
    }

    public function __invoke(Request $request, SerializerInterface $serializer): JsonResponse
    {
        $query = $request->query->get('q', '');
        $indexName = $request->query->get('index', '');

        if (!$query || !$indexName) {
            $dto = new SearchResult([], 0);
        } else {
            $searchResult = $this->meilisearch->index($indexName)->search($query, ["matchingStrategy" => "last"]);
            $dto = new SearchResult($searchResult->getHits(), $searchResult->getEstimatedTotalHits());
        }
        // Sérialiser le DTO en JSON selon le groupe "search"
        $json = $serializer->serialize($dto, 'json', ['groups' => ['search']]);
        // Retourner le JSON avec le flag `$json = true` (puisqu’on donne une string JSON)
        return new JsonResponse($json, 200, [], true);
    }
}
