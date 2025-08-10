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
            $id = $uriVariables['id'] ?? null;
            /** @var MediaObject */
            $media = $this->entityManager->getRepository(MediaObject::class)
                ->find($id);  // Trouver le produit par son ID
            $media->setImageNotExist(true);
            $this->entityManager->flush();
            return  $media;
        }
        // Appeler le processeur par défaut (continue la suppression du produit)
        return $this->processor->process($data, $operation, $uriVariables, $context);
    }
}
