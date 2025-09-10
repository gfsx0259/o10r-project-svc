<?php

declare(strict_types=1);

namespace App\Fixture\Gateway;

class ResourceDto
{
    /**
     * @param string $path
     * @param string $contentPath
     */
    public function __construct(
        public string $path,
        public string $contentPath,
    ) {}
}
