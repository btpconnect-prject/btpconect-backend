<?php

namespace App\Services;

use App\Entity\Order;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class MessageService
{
    const TELEGRAM_API_URL = 'https://api.telegram.org/__API_KEY__/sendMessage';
    public function __construct(
        private readonly HttpService $httpService,
        private LoggerInterface $logger,
        private ParameterBagInterface $params
    ) {}
    public function sendMessageTelegram(string $message): mixed
    {
        $telegramApiKey = $this->params->get("app.telegram.apikey");
        $telegramChatId = $this->params->get("app.telegram.chat.id");

        if (!$telegramApiKey || !$telegramChatId) {
            $this->logger->error('Missing  Telegram configuration parameters ');
            throw new Exception('Missing   Telegram configuration parameters');
        }

        $url  = str_replace('__API_KEY__', $telegramApiKey, self::TELEGRAM_API_URL);

        $data = [
            'chat_id' => $telegramChatId,
            'text'    => $message,
        ];

        try {
            // Envoi de la requête pour obtenir un token
            $response = $this->httpService->postData($url, [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'body' => $data,
            ]);

            if ($response->getStatusCode() === 200) {
                return $response->getContent();
            }
        } catch (Exception $e) {
            // Gestion des erreurs
            throw new \RuntimeException('Error contacting Keycloak: ' . $e->getMessage());
        }

        // Default return in case no value is returned earlier
        return null;
    }

    public function madeMessage(array $order): string
    {

        if (empty($order)) {
            throw new \InvalidArgumentException('Order data cannot be empty');
        }





        $message = sprintf(
            "\n   ------NOUVELLE COMMANDE------  \n\n Nom & Prenom: %s %s\n Adresse: %s - %s \n Email: %s \n Télephone: %s\n\n****ARTICLES  DANS LA COMMANDE****",
            $order["order"]['customer']['name'],
            $order["order"]['customer']['firstname'],
            $order["order"]['customer']['adresse'],
            $order["order"]['customer']['postalCode'],
            $order["order"]['customer']['email'],
            $order["order"]['customer']['phone'],
        );

        $productInOrder = $order["order"]["cart"];
        $count  = 1;
        $totalPrice = 0;
        foreach ($productInOrder as $key => $value) {
            $currentVal  =  json_decode($value, true);
            $totalPrice += $currentVal['price'];
            $message    .= sprintf(
                "\n %s: %s \n Prix: %s \n Quantité: %s \n",
                $count,
                $currentVal['name'],
                $currentVal['price'] . "CFA",
                $currentVal['quantity']
            );
        }
        $message .= sprintf("\n Total: %s CFA \n", $totalPrice);
        $message .= "Rendez-vous sur le site https://btpConnects.com pour suivre la commande, N°: " . $order["order"]['id'] . "\n\n";

        return $message;
    }
}
