<?php

namespace App\State;


use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\MediaObject;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\Metadata\PutOperation;
use App\Entity\ProductEntity;

/**
 * @implements ProcessorInterface<MediaObject, MediaObject|void>
 */
class MediaProcessor implements ProcessorInterface
{

    public function __construct(
        private ProcessorInterface $processor,
        private EntityManagerInterface $entityManager
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        // Vérifier si nous traitons une suppression (DELETE)
        if ($operation instanceof DeleteOperationInterface && $data instanceof MediaObject) {

            // 1. On cherche tous les produits qui utilisent ce MediaObject
            /** @var ProductEntity[] */
            $products = $this->entityManager->getRepository(ProductEntity::class)->findAll();

            foreach ($products as $product) {
                $modified = false;

                // Cas 1 : image principale
                if ($product->getImage()?->getId() === $data->getId()) {
                    $product->setImage(null);
                    $modified = true;
                }

                // Cas 2 : shots (galerie)
                if ($product->getShots() && $product->getShots()->contains($data)) {
                    $product->removeShot($data);
                    $modified = true;
                }

                if ($modified) {
                    $this->entityManager->persist($product);
                }
            }

            // 2. On flush les changements
            $this->entityManager->flush();

            // 3. On peut maintenant supprimer le MediaObject
            $this->entityManager->remove($data);
            $this->entityManager->flush();

            return null;
        }

        // Appeler le processeur par défaut (continue la suppression du produit)
        return $this->processor->process($data, $operation, $uriVariables, $context);
    }
}
