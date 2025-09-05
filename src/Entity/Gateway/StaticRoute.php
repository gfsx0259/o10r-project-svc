<?php

declare(strict_types=1);

namespace App\Entity\Gateway;

use App\Repository\StaticRouteRepository;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;

#[Entity(repository: StaticRouteRepository::class, table: 'static', readonlySchema: true)]
#[Column(type: 'primary', name: 'id')]
#[Column(type: 'integer', name: 'scenario_id', property: 'scenarioId')]
#[Column(type: 'string', name: 'path')]
#[Column(type: 'json', name: 'body')]
class StaticRoute
{
    private int $id;
    private int $scenarioId;
    private string $path;
    private string $body;

    public function getId(): int
    {
        return $this->id;
    }

    public function getScenarioId(): int
    {
        return $this->scenarioId;
    }

    public function setScenarioId(int $scenarioId): void
    {
        $this->scenarioId = $scenarioId;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): void
    {
        $this->body = $body;
    }
}
