<?php

declare(strict_types=1);

namespace App\Dto;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Method',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'code', type: 'string', example: 'card'),
        new OA\Property(property: 'title', type: 'string', example: 'Card'),
        new OA\Property(property: 'description', type: 'string', example: 'Payment with bank cards'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'MethodCreate',
    properties: [
        new OA\Property(property: 'code', type: 'string', example: 'card'),
        new OA\Property(property: 'title', type: 'string', example: 'Card'),
        new OA\Property(property: 'description', type: 'string', example: 'Payment with bank cards'),
    ],
    type: 'object'
)]
final readonly class MethodDto
{
    public function __construct(
        public int $id,
        public string $code,
        public string $title,
        public string $description,
    ) {}
}
