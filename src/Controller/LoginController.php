<?php

namespace App\Controller;

use App\Entity\UserEntity;
use App\Model\UserLoginDto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

class LoginController extends AbstractController
{

    private $jwtManager;
    private $passwordEncoder;
    private $entityManager;

    public function __construct(
        JWTTokenManagerInterface $jwtManager,
        UserPasswordHasherInterface $passwordEncoder,
        EntityManagerInterface $entityManager,
        private readonly \App\Services\MessageService $messageService
    ) {
        $this->jwtManager = $jwtManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
    }

    public function __invoke(#[MapRequestPayload] UserLoginDto $userLogin): mixed
    {
        $user = $this->entityManager->getRepository(UserEntity::class)->findOneBy(['email' => $userLogin->email]);

        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        }

        if (!$this->passwordEncoder->isPasswordValid($user, $userLogin->password)) {
            return $this->json(['error' => 'Invalid password'], 401);
        }

        $token = $this->jwtManager->create($user);

        return $this->json([
            'token' => $token
        ]);
    }
}
