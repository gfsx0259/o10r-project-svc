<?php

declare(strict_types=1);

namespace App\Fixture\Gateway;

class ScenarioDto
{
    /**
     * @param string $title
     * @param string[] $callbacks
     */
    public function __construct(
        public string $title,
        public array $callbacks = [],
    ) {}
}
