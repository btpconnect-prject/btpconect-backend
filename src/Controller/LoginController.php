<?php

namespace App\Controller;

use App\Dto\UserLoginDto as DtoUserLoginDto;
use App\Entity\UserEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Cookie;


class LoginController extends AbstractController
{

    private $jwtManager;
    private $passwordEncoder;
    private $entityManager;

    public function __construct(
        JWTTokenManagerInterface $jwtManager,
        UserPasswordHasherInterface $passwordEncoder,
        EntityManagerInterface $entityManager,
        private ParameterBagInterface $params,
        private readonly \App\Services\MessageService $messageService
    ) {
        $this->jwtManager = $jwtManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
    }

    #[Route('api/v1/user/login', name: 'user_login', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] DtoUserLoginDto $userLogin): mixed
    {
        /** @var UserEntity $user */
        $user = $this->entityManager->getRepository(UserEntity::class)->findOneBy(['email' => $userLogin->email]);

        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        }

        if (!$this->passwordEncoder->isPasswordValid($user, $userLogin->password)) {
            return $this->json(['error' => 'Invalid password'], 401);
        }

        $isDev =  $this->params->get('app.env') == "dev";
        $token = $this->jwtManager->create($user);
        // Création du cookie avec SameSite=None et Secure pour le contexte CORS
        $cookie = new Cookie(
            'token', // nom du cookie
            $token, // valeur du cookie
            strtotime('tomorrow'), // expiration
            '/', // path
            null, // domain
            true, // Secure (envoi uniquement sur HTTPS)
            true, // HTTPOnly (inaccessible via JavaScript)
            false, // SameSite=None
            $isDev ? 'None': 'None' // SameSite=None pour permettre l'envoi du cookie dans un contexte inter-origines
        );

        // Création de la réponse avec le cookie
        $response = $this->json([
            'id' => $user->getId(),
            'email' => $user->getUserIdentifier(), // ou getEmail() si défini
            'roles' => $user->getRoles(),
            'username' => $user->getUserIdentifier(),
            'name' => $user->getName(),
            'image' => $user->getProfilePicture(),
            'firstname' => $user->getFirstname(),
            'isadmin' => in_array( "ROLE_ADMIN", $user->getRoles()),
            'token' => $token,
            'message' => 'Login successful',
        ]);

        // Ajout du cookie à la réponse
        $response->headers->setCookie($cookie);
        return  $response;
    }
}
