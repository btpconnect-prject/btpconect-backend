<?php

namespace App\Services;

use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ProductDescriptionGenerator
{
    const GENERATE_IMAGE = "/generate_image";
    const GENERATE_RESPONSE = "/generate_response";

    public function __construct(
        private LoggerInterface $logger,
        private ParameterBagInterface $params,
        private HttpClientInterface $client,

    ) {}




    function generateImage(string $prompt): string
    {

        $url = $this->params->get("dynask.spak.api") . self::GENERATE_IMAGE;
        $response = $this->client->request('GET', $url, [
            'query' => [
                'user_input' => $prompt,
                'width'      => 1024,
                'height'     => 768,
                'model'      => 'flux',
                'nologo'     => 'true',
            ],
        ]);

        $status = $response->getStatusCode();
        if ($status !== 200) {
            throw new \RuntimeException("Erreur API DynaSpark : HTTP $status");
        }

        $content = $response->getContent();
        $json = json_decode($content, true);
        if (json_last_error()) {
            throw new \RuntimeException('JSON invalide: ' . json_last_error_msg());
        }

        return $json['image_url'];
    }


    public function generatedManyImages(string $prompt, int $number = 1): array
    {

        $imageUrls = [];
        $promptToArray = explode("|", $prompt);
        foreach ($promptToArray as $currentPrompt)
            $imageUrls[] = $this->generateImage($currentPrompt);

        return $imageUrls;
    }


    function generateProductSheet(string $prompt)
    {
        // Construction du prompt avec le nom du produit injecté
        $url = $this->params->get("dynask.spak.api") . self::GENERATE_RESPONSE;
        $response = $this->client->request('GET', $url, [
            'query' => [
                'user_input' => $prompt,
                'json' => 'true',
            ],
        ]);

        $statusCode = $response->getStatusCode();
        if ($statusCode !== 200) {
            throw new \RuntimeException("Erreur API DynaSpark : HTTP $statusCode");
        }

        $content = $response->getContent();
        $data = json_decode($content, true);
        if (json_last_error()) {
            throw new \RuntimeException('Réponse JSON invalide: ' . json_last_error_msg());
        }


        $statusCode = $response->getStatusCode();
        if ($statusCode !== 200) {
            throw new \RuntimeException("Erreur API DynaSpark : HTTP $statusCode");
        }

        $content = $response->getContent();
        $data = json_decode($content, true);
        if (json_last_error()) {
            throw new \RuntimeException('Réponse JSON invalide: ' . json_last_error_msg());
        }
        // Le JSON renvoyé devrait déjà respecter ta structure
        return $data;
    }



    public function generate(string $prompt): array
    {
        $url = $this->params->get("dynask.spak.api") . self::GENERATE_RESPONSE;
        try {
            $response = $this->client->request('GET', $url, [
                'query' => [
                    'user_input' => $prompt,
                    'json' => 'true',
                ],
            ]);

            return ($response->toArray());
        } catch (ClientExceptionInterface $e) {
            $statusCode = $e->getResponse()?->getStatusCode() ?? 0;
            $errorContent = $e->getResponse()?->getContent(false) ?? 'No response';

            throw new \RuntimeException("Erreur OpenRouter ($statusCode): $errorContent", $statusCode);
        }
    }
}
