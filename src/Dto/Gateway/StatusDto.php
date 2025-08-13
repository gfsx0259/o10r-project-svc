<?php

declare(strict_types=1);

namespace App\Dto\Gateway;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Status',
    properties: [
        new OA\Property(property: 'status', type: 'string', example: 'success'),
        new OA\Property(property: 'project_id', type: 'integer', example: 100),
        new OA\Property(property: 'payment_id', type: 'string', example: 'EP_ASD123'),
    ],
    type: 'object'
)]
final readonly class StatusDto
{
    public function __construct(
        public string $status,
        public int $project_id,
        public string $payment_id,
    ) {}
}
