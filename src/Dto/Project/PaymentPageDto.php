<?php

declare(strict_types=1);

namespace App\Dto\Project;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PaymentPageDto',
    properties: [
        new OA\Property(property: 'project', ref: '#/components/schemas/PaymentPageProject'),
        new OA\Property(property: 'methods', type: 'array', items: new OA\Items(ref: '#/components/schemas/Method')),
        new OA\Property(property: 'settings', type: 'object', example: ["return_url" => "https://example.com/return", "color" => "blue"]),
    ],
    type: 'object'
)]
final readonly class PaymentPageDto
{
    public function __construct(
        public PaymentPageProjectDto $project,
        public array $methods,
        public array $settings,
    ) {}
}
