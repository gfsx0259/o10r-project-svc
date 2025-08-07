<?php

declare(strict_types=1);

namespace App\Dto\Gateway;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Route',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'method_id', type: 'integer', example: 1),
        new OA\Property(property: 'conditions', type: 'string', example: '{}'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'RouteCreate',
    properties: [
        new OA\Property(property: 'method_id', type: 'integer', example: 1),
        new OA\Property(property: 'conditions', type: 'string', example: '{}'),
    ],
    type: 'object'
)]
final readonly class RouteDto
{
    public function __construct(
        public int $id,
        public int $method_id,
        public string $conditions,
    ) {}
}
