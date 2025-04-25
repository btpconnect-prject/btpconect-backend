<?php

namespace App\Controller;

use App\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SendMessageConfirmController extends AbstractController {


    public function __construct(
        private readonly \App\Services\MessageService $messageService
    ) {}

    #[Route(
        name: 'message_sendconfirmation',
        path: '/order/{id}/sendconfirmation', 
        methods: ['POST'],
        defaults: [
            '_api_resource_class' => Order::class,
            '_api_item_operation_name' => 'order_sendconfirmation',
        ],
    )]
    public function __invoke(Order $order)
    {

        if (!$order) {
            // Handle the case where the order is not found
            return $this->json(['error' => 'Order not found'], 404);
        }
        $message = $this->messageService->madeMessage($order);
        //$this->messageService->sendMessageTelegram($message);

        return $this->json([
            'message' => $order->getId(),
        ]);

        
    }



}
