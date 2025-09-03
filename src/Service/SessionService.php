<?php

declare(strict_types=1);

namespace App\Service;

use GuzzleHttp\Client;

class SessionService
{
    private Client $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client([
            'base_uri' => $_ENV['SESSION_HOST'],
            'verify' => false,
        ]);
    }

    public function makeSession(int $projectId, string $paymentId, int $amount, string $currency): string
    {
        $content = $this->httpClient->post(
            '/v1/session',
            [
                'json' => [
                    'general' => [
                        'project_id' => $projectId,
                        'payment_id' => $paymentId,
                    ],
                    'payment' => [
                        'amount' => $amount,
                        'currency' => $currency,
                    ]
                ],
            ]
        )->getBody()->getContents();

        $data = json_decode($content, true);

        return $data['data']['client_secret'];
    }
}
