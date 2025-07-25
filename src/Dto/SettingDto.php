<?php

declare(strict_types=1);

namespace App\Dto;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Setting',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 9),
        new OA\Property(property: 'code', type: 'string', example: 'return_url'),
        new OA\Property(property: 'value', type: 'string', example: 'https://ya.ru'),
        new OA\Property(property: 'group', type: 'string', example: 'navigation'),
    ],
    type: 'object'
)]
final readonly class SettingDto
{
    public function __construct(
        public string $code,
        public string $value,
        public string $group,
    ) {}
}
