<?php

namespace App\Controller;

use App\Entity\ProductEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class UpdateProductController extends AbstractController
{

    public function __construct(private EntityManagerInterface $entityManager) {}
    public function __invoke(ProductEntity $data)
    {

        // Si le produit existe déjà, nous procédons à la mise à jourz
        $existingProduct = $this->entityManager->getRepository(ProductEntity::class)
            ->find($data->getId());  // Trouver le produit par son ID

        if ($existingProduct) {
            // Mettre à jour les propriétés du produit existant
            $existingProduct->setProductName($data->getProductName());
            $existingProduct->setCurrentPrice($data->getCurrentPrice());
            $existingProduct->setCoverImage($data->getCoverImage());
            $existingProduct->setPreviousPrice($data->getPreviousPrice());
            $existingProduct->setRating($data->getRating());
            $existingProduct->setJustIn($data->isJustIn());
            $existingProduct->setPiecesSold($data->getPiecesSold());
            $existingProduct->setCategory($data->getCategory());
            $existingProduct->setIsFeatured($data->isisFeatured());
            $existingProduct->setImage($data->getImage());
            // Vous pouvez ajouter ici d'autres propriétés à mettre à jour

            // Sauvegarder les modifications dans la base de données
            $this->entityManager->flush();
            return $this->json($existingProduct, Response::HTTP_OK);  // Retourner l'entité mise à jour
        }

        return $this->json([], Response::HTTP_NOT_FOUND);
    }
}
