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


        if (!($data instanceof Order)) {
            throw new \InvalidArgumentException('Expected instance of Order.');
        }
        /** @var Order $orderData */

        $orderData = $data;
        $message = $this->messageService->madeMessage($orderData);  
        $this->messageService->sendMessageTelegram($message);
        return null;
    }
}
