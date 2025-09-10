<?php

declare(strict_types=1);

namespace App\Fixture\Catalog;

class MethodDto
{
    public function __construct(
        public string $code,
        public string $title,
        public string $description,
    ) {}
}
