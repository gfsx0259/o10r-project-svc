<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Project\Project;
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

    public function makeSession(Project $project, string $paymentId, int $amount, string $currency, ?string $paymentDescription, ?int $customerId): string
    {
        $data = [
            'general' => [
                'project_id' => $project->getId(),
                'payment_id' => $paymentId,
            ],
            'payment' => [
                'amount' => $amount,
                'currency' => $currency,
            ]
        ];

        if ($paymentDescription) {
            $data['payment']['description'] = $paymentDescription;
        }

        if ($customerId) {
            $data['customer'] = ['id' => $customerId];
        }

        $content = $this->httpClient->post('/v1/session', [
            'headers' => [
                'Authorization' => 'Bearer ' . $project->getSecretKey(),
            ],
            'json' => $data
        ])->getBody()->getContents();

        $response = json_decode($content, true);

        return $response['data']['client_secret'];
    }
}
