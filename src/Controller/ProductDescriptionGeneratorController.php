<?php

// src/Controller/SearchController.php

namespace App\Controller;

use App\Dto\ProductDescriptionGeneratorDto;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Services\ProductDescriptionGenerator;

use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class ProductDescriptionGeneratorController
{

    const DEFAULT_FORMAT="json";

    public function __construct(private ProductDescriptionGenerator $productDescriptionGenerator)
    {}
        
    public function __invoke(Request $request, SerializerInterface $serializer): JsonResponse
    {
        
        $query  = $request->query->get('prompt', '');
        $format = $request->query->get('format', '');
        
        
        if (!$query) {
            $dto = new ProductDescriptionGeneratorDto([]);
        } else {
             $response = $this->productDescriptionGenerator->generate( $query);
             $jsonString = $response['choices'][0]['message']['content'];
             
             $dto  = self::DEFAULT_FORMAT == $format ? json_decode($jsonString, true) : new ProductDescriptionGeneratorDto($response);
             
        }
        // Sérialiser le DTO en JSON selon le groupe "search"
        $json = $serializer->serialize($dto, 'json', ['groups' => ['search']]);
        // Retourner le JSON avec le flag `$json = true` (puisqu’on donne une string JSON)
        return new JsonResponse($json, 200, [], true);
    }
}
