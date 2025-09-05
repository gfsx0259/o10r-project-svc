<?php

declare(strict_types=1);

namespace App\Dto\Gateway;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'StaticRoute',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'scenario_id', type: 'integer', example: 1),
        new OA\Property(property: 'path', type: 'string', example: '/some/path'),
        new OA\Property(property: 'body', type: 'string', example: '{}'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'StaticRouteCreate',
    properties: [
        new OA\Property(property: 'scenario_id', type: 'integer', example: 1),
        new OA\Property(property: 'path', type: 'string', example: '/some/path'),
        new OA\Property(property: 'body', type: 'string', example: '{}'),
    ],
    type: 'object'
)]
final readonly class StaticRouteDto
{
    public function __construct(
        public int $id,
        public int $scenario_id,
        public string $path,
        public string $body,
    ) {}
}
