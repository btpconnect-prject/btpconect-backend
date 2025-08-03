<?php

namespace App\State;


use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\ProductEntity;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\Metadata\PutOperation;

/**
 * @implements ProcessorInterface<ProductEntity, ProductEntity|void>
 */
class ProductProcessorPost implements ProcessorInterface
{

    public function __construct(
        private ProcessorInterface $processor,
        private EntityManagerInterface $entityManager
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        // Vérifier si nous traitons une suppression (DELETE)
        if ($operation instanceof DeleteOperationInterface  && $data instanceof ProductEntity) {
            // Appeler la méthode pour dissocier les médias avant la suppression
            $data->dissociateMediaBeforeDelete();
            // Enregistrer les modifications dans la base de données
            $this->entityManager->remove($data);
            $this->entityManager->flush();
            return null;
        }


        // Vérifier si l'opération est une mise à jour (PUT) ou une création (POST)
        if (str_contains($operation->getName(), "put") &&  $data instanceof ProductEntity) {
            $productId = $uriVariables['id'] ?? null;

            // Si le produit existe déjà, nous procédons à la mise à jourz**
            /** @var ProductEntity */
            $existingProduct = $this->entityManager->getRepository(ProductEntity::class)
                ->find($productId);  // Trouver le produit par son ID

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
                $existingProduct->setDescription($data->getDescription() ?? "");
                $existingProduct->setDetails($data->getDetails() ?? "");
                $existingProduct->setDeliveryDetails($data->getDeliveryDetails() ?? "");
                $existingProduct->setProductCaractors($data->getProductCaractors() ?? []);
                $existingProduct->setIsVerified($data->isVerified());

                $promotions = $data->getPromotions();
                foreach ($promotions as $promos) {
                    if (!$existingProduct->getPromotions()->contains($promos)) {
                        $existingProduct->addPromotion($promos);
                    }
                }
                $shots = $data->getShots();
                foreach ($shots as $shot) {
                    if (!$existingProduct->getShots()->contains($shot)) {
                        $existingProduct->addShot($shot);
                    }
                }
                // Dissocier les anciens "shots" si nécessaire
                foreach ($existingProduct->getShots() as $existingShot) {
                    if (!$shots->contains($existingShot)) {
                        $existingProduct->removeShot($existingShot);
                    }
                }
                // Vous pouvez ajouter ici d'autres propriétés à mettre à jour
                foreach ($existingProduct->getPromotions() as $existingpromotions) {
                    if (!$promotions->contains($existingpromotions)) {
                        $existingProduct->removeShot($existingpromotions);
                    }
                }
                // Sauvegarder les modifications dans la base de données
                $this->entityManager->flush();
                return $existingProduct;  // Retourner l'entité mise à jour
            }
        }

        // Appeler le processeur par défaut (continue la suppression du produit)
        return $this->processor->process($data, $operation, $uriVariables, $context);
    }
}
