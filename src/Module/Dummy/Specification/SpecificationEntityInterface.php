<?php

namespace App\Module\Dummy\Specification;

/**
 * Specifies entity with specification rules set
 */
interface SpecificationEntityInterface
{
    public function getId(): int;
    public function getSpecification(): array;
}
