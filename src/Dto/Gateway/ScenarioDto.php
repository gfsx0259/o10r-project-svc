<?php

declare(strict_types=1);

namespace App\Dto\Gateway;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Scenario',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'route_id', type: 'integer', example: 1),
        new OA\Property(property: 'title', type: 'string', example: 'Successfully scenario'),
        new OA\Property(property: 'conditions', type: 'string', example: '{}'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'ScenarioCreate',
    properties: [
        new OA\Property(property: 'route_id', type: 'integer', example: 1),
        new OA\Property(property: 'title', type: 'string', example: 'Successfully scenario'),
        new OA\Property(property: 'conditions', type: 'string', example: '{}'),
    ],
    type: 'object'
)]
final readonly class ScenarioDto
{
    public function __construct(
        public int $id,
        public int $route_id,
        public string $title,
        public string $conditions,
    ) {}
}
