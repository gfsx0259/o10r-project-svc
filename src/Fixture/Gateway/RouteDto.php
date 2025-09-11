<?php

declare(strict_types=1);

namespace App\Fixture\Gateway;

class RouteDto
{
    /**
     * @param string $methodCode
     * @param ScenarioDto[] $scenarios
     */
    public function __construct(
        public string $methodCode,
        public array $scenarios = [],
    ) {}
}
