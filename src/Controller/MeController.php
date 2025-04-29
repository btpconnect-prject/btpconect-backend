<?php

// src/Controller/MeController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\UserEntity;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\ExpressionLanguage\Expression;

class MeController extends AbstractController
{
    #[IsGranted(new Expression('is_authenticated()'))]
    #[Route("api/user/getMe",  name: "get_user_me", methods: ["GET"])]
    public function __invoke(): JsonResponse
    {
        /** @var UserEntity|null $user */
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'Unauthorized'], 401);
        }

        // Tu peux adapter ici selon les infos que tu veux exposer
        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getUserIdentifier(), // ou getEmail() si défini
            'roles' => $user->getRoles(),
        ]);
    }
}
