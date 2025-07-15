<?php

// src/Controller/ProductBySlugController.php
namespace App\Controller;

use App\Entity\ProductEntity;
use App\Repository\ProductEntityRepository;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\SerializerInterface;

#[AsController]
class ProductBySlugController
{
    public function __invoke(string $slug, ProductEntityRepository $repo, SerializerInterface $serializer): Response
    {
        $product = $repo->findOneBy(['slug' => $slug]);


        if (!$product) {
            throw new NotFoundHttpException(sprintf('Product with slug "%s" not found.', $slug));
        }

        return new Response(
            $serializer->serialize($product, 'json', ['groups' => ['product::read', "category:read"], 'enable_max_depth' => true]),
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );

         
    }
}
