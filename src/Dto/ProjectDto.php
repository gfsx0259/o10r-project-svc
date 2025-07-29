<?php

declare(strict_types=1);

namespace App\Dto;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Project',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 25),
        new OA\Property(property: 'code', type: 'string', example: 'funny-goose'),
        new OA\Property(property: 'secret_key', type: 'string', example: 'abc123xyz'),
        new OA\Property(property: 'is_sandbox', type: 'integer', example: 0),
        new OA\Property(property: 'methods', type: 'array', items: new OA\Items(ref: '#/components/schemas/Method'), example: [1, 2, 3]),
        new OA\Property(property: 'settings', type: 'array', items: new OA\Items(ref: '#/components/schemas/Setting')),
    ],
    type: 'object'
)]
final readonly class ProjectDto
{
    public function __construct(
        public int $id,
        public string $code,
        public string $secret_key,
        public int $is_sandbox,
        public array $methods,
        public array $settings,
    ) {}
}
