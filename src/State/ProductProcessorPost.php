<?php

namespace App\State;


use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\ProductEntity;
use Doctrine\ORM\EntityManagerInterface;

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
        if (isset($context['operation']) && $context['operation']->getMethod() === 'DELETE' && $data instanceof ProductEntity) {
            // Appeler la méthode pour dissocier les médias avant la suppression
            $data->dissociateMediaBeforeDelete();

            // Enregistrer les modifications dans la base de données
            $this->entityManager->flush();
        }

        // Appeler le processeur par défaut (continue la suppression du produit)
        return $this->processor->process($data, $operation, $uriVariables, $context);
    }
}
