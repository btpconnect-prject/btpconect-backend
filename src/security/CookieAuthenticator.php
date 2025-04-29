<?php

// src/Security/ApiKeyAuthenticator.php
namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authenticator\JWTAuthenticator;

class CookieAuthenticator extends AbstractAuthenticator
{

    public function __construct(
        private JWTTokenManagerInterface $jwtManager,
        private JWTAuthenticator $jwtAuthenticator
    ) {}
    /**
     * @param string $token
     * @return string
     */
    protected function formatToken(string $token): string
    {
        return trim(preg_replace('/^(?:\s+)?[B-b]earer\s/', '', $token));
    }
    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning `false` will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request): ?bool
    {
        // "auth-token" is an example of a custom, non-standard HTTP header used in this application
        return $request->cookies->has('token') || $request->headers->has('Authorization');
    }

    public function authenticate(Request $request): Passport
    {
        $token = $request->headers->get('Authorization') ?? $request->cookies->get('token');
        if (null === $token || empty($token)) {
            // The token header was empty, authentication fails with HTTP Status
            // Code 401 "Unauthorized"
            throw new CustomUserMessageAuthenticationException('Token is not present in the request');
        }

        $formattedToken = $this->formatToken($token);
        $data = $this->jwtManager->parse($formattedToken);

        if (!isset($data['username'])) {
            throw new CustomUserMessageAuthenticationException('Invalid token data');
        }

        return new SelfValidatingPassport(new UserBadge($data['username']));

    
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // on success, let the request continue
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            // you may want to customize or obfuscate the message first
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
}
