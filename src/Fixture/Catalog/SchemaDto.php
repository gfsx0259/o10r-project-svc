<?php

declare(strict_types=1);

namespace App\Fixture\Catalog;

class SchemaDto
{
    public function __construct(
        public string $methodCode,
        public string $contentPath,
    ) {}
}
