<?php

declare(strict_types=1);

namespace App\Entity\Gateway;

use App\Module\Dummy\Specification\SpecificationEntityInterface;
use App\Repository\ScenarioRepository;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\HasMany;
use Doctrine\Common\Collections\ArrayCollection;

#[Entity(repository: ScenarioRepository::class, table: 'scenario', readonlySchema: true)]
#[Column(type: 'primary', name: 'id')]
#[Column(type: 'integer', name: 'route_id', property: 'routeId')]
#[Column(type: 'string', name: 'title')]
#[Column(type: 'json', name: 'conditions')]
class Scenario implements SpecificationEntityInterface
{
    private int $id;
    private int $routeId;
    private string $conditions;
    private string $title;

    #[HasMany(
        target: Callback::class,
        innerKey: 'id',
        outerKey: 'scenario_id',
        orderBy: ['order' => 'asc']
    )]
    private ArrayCollection $callbacks;

    public function __construct() {
        $this->callbacks = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getRouteId(): int
    {
        return $this->routeId;
    }

    public function getConditions(): string
    {
        return $this->conditions;
    }

    public function setRouteId(int $routeId): void
    {
        $this->routeId = $routeId;
    }

    public function setConditions(string $conditions): void
    {
        $this->conditions = $conditions;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getSpecification(): array
    {
        return json_decode($this->conditions, true);
    }

    public function getCallbacks(): ArrayCollection
    {
        return $this->callbacks;
    }
}
