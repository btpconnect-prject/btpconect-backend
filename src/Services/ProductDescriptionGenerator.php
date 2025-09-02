<?php

namespace App\Services;

use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ProductDescriptionGenerator
{
    public function __construct(
        private LoggerInterface $logger,
        private ParameterBagInterface $params,
        private HttpClientInterface $client,

    ) {}



        public function generate(string $prompt): array
    {
        $apiKey = $this->params->get("open.router.ai.api/keys");
        try {
            $response = $this->client->request('POST', 'https://openrouter.ai/api/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt],
                    ],
                ],
            ]);

            return $response->toArray();
        } catch (ClientExceptionInterface $e) {
            $statusCode = $e->getResponse()?->getStatusCode() ?? 0;
            $errorContent = $e->getResponse()?->getContent(false) ?? 'No response';

            throw new \RuntimeException("Erreur OpenRouter ($statusCode): $errorContent", $statusCode);
        }
    }
}
