<?php

declare(strict_types=1);

namespace App\Entity\Gateway;

use App\Repository\RouteRepository;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;

#[Entity(repository: RouteRepository::class, table: 'route', readonlySchema: true)]
#[Column(type: 'primary', name: 'id')]
#[Column(type: 'integer', name: 'method_id', property: 'methodId')]
#[Column(type: 'json', name: 'conditions')]
class Route
{
    private int $id;
    private int $methodId;
    private string $conditions;

    public function getId(): int
    {
        return $this->id;
    }

    public function getMethodId(): int
    {
        return $this->methodId;
    }

    public function getConditions(): string
    {
        return $this->conditions;
    }

    public function setMethodId(int $methodId): void
    {
        $this->methodId = $methodId;
    }

    public function setConditions(string $conditions): void
    {
        $this->conditions = $conditions;
    }
}
