<?php

declare(strict_types=1);

namespace App\Dto\Catalog;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MethodFormSchema',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'method_id', type: 'integer', example: 1),
        new OA\Property(property: 'fields', type: 'string', example: '{}'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'MethodFormSchemaCreate',
    properties: [
        new OA\Property(property: 'method_id', type: 'integer', example: 1),
        new OA\Property(property: 'fields', type: 'string', example: '{}'),
    ],
    type: 'object'
)]
final readonly class MethodFormSchemaDto
{
    public function __construct(
        public int $id,
        public string $method_id,
        public string $fields,
    ) {}
}
