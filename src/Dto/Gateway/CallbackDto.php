<?php

declare(strict_types=1);

namespace App\Dto\Gateway;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Callback',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'scenario_id', type: 'integer', example: 1),
        new OA\Property(property: 'order', type: 'integer', example: 1),
        new OA\Property(property: 'body', type: 'string', example: '{}'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'CallbackCreate',
    properties: [
        new OA\Property(property: 'scenario_id', type: 'integer', example: 1),
        new OA\Property(property: 'order', type: 'integer', example: 1),
        new OA\Property(property: 'body', type: 'string', example: '{}'),
    ],
    type: 'object'
)]
final readonly class CallbackDto
{
    public function __construct(
        public int $id,
        public int $scenario_id,
        public string $body,
        public int $order,
    ) {}
}
