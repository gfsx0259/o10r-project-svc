<?php

declare(strict_types=1);

namespace App\Entity\Gateway;

use App\Repository\CallbackRepository;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;

#[Entity(repository: CallbackRepository::class, table: 'callback', readonlySchema: true)]
#[Column(type: 'primary', name: 'id')]
#[Column(type: 'integer', name: 'scenario_id', property: 'scenarioId')]
#[Column(type: 'json', name: 'body')]
#[Column(type: 'tinyInteger', name: 'order')]
class Callback
{
    private int $id;
    private int $scenarioId;
    private string $body;
    private int $order;

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

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    public function getOrder(): int
    {
        return $this->order;
    }

    public function setOrder(int $order): void
    {
        $this->order = $order;
    }
}
