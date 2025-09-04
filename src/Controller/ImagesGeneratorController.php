<?php

// src/Controller/SearchController.php

namespace App\Controller;

use App\Dto\ImagesGeneratorDto;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Services\ProductDescriptionGenerator;

use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class ImagesGeneratorController
{

    public function __construct(private ProductDescriptionGenerator $productDescriptionGenerator)
    {}
        
    public function __invoke(Request $request, SerializerInterface $serializer): JsonResponse
    {
        
                $query   = $request->query->get('prompt', '');
        
        if (!$query) {
            $dto         = new ImagesGeneratorDto([]);
        } else {
              $dto   = $this->productDescriptionGenerator->generatedManyImages($query);
             $dto        = ["images"=>$dto ];
        }
        // Sérialiser le DTO en JSON selon le groupe "search"
        $json = $serializer->serialize($dto, 'json', ['groups' => ['search']]);
        // Retourner le JSON avec le flag `$json = true` (puisqu’on donne une string JSON)
        return new JsonResponse($json, 200, [], true);
    }
}
