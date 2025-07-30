<?php

declare(strict_types=1);

namespace App\Dto;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PaymentPageProject',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 25),
        new OA\Property(property: 'hash', type: 'string', example: '03d59e663c1af9ac33a9949d1193505a'),
    ],
    type: 'object'
)]
final readonly class PaymentPageProjectDto
{
    public function __construct(
        public int $id,
        public string $hash,
    ) {}
}
