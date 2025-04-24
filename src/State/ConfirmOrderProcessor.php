<?php
// api/src/State/UserPasswordHasher.php

namespace App\State;

use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Order;
use App\Services\MessageService;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @implements ProcessorInterface<Order, Order|void>
 */
final readonly class ConfirmOrderProcessor implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $processor,
        private EntityManagerInterface $entityManager,
        private readonly MessageService $messageService
    ) {}

    /**
     * @param Order
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {


        if ($operation instanceof DeleteOperationInterface  && $data instanceof Order) {
            // Appeler la méthode pour dissocier les médias avant la suppression
            // Enregistrer les modifications dans la base de données
            $this->entityManager->remove($data);
            $this->entityManager->flush();
            return null;
        }


        /** @var Order $order */
        $order = $data;

        $this->messageService->madeMessage($order);        

        $result = $this->processor->process($order, $operation, $uriVariables, $context);
        return $result;
    }

    
}
