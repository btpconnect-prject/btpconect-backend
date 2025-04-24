<?php

namespace App\Controller;

use App\Entity\MediaObject;
use App\Services\MessageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class OrderController extends AbstractController
{

    public function __construct(private readonly MessageService $messageService) {}
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function __invoke(Request $request)
    {

        $data = json_decode($request->getContent(), true);
        $message = $this->messageService->madeMessage($data);
        $this->messageService->sendMessageTelegram($message);

        return $this->json([
            'status' => 'success',
            'message' => 'Message sent successfully',
        ]);
    }
}
