<?php

namespace App\Module\Dummy;

use OpenApi\Attributes as OA;
use Yiisoft\Validator\Rule\Required;
use Yiisoft\Validator\Rule\Integer;
use Yiisoft\Validator\Rule\Each;

#[OA\Schema(
    schema: 'Mode',
    required: ['scenarioId'],
    properties: [
        new OA\Property(
            property: 'scenarioId',
            description: 'Available scenario id',
            type: 'integer',
            example: 123
        ),
        new OA\Property(
            property: 'staticRoutesIds',
            description: 'Available static routes',
            type: 'array',
            items: new OA\Items(type: 'integer'),
            example: [1, 2, 3]
        ),
    ],
    type: 'object'
)]
final class Mode
{
    #[Required]
    #[Integer]
    private int $scenarioId;

    #[Each(new Integer())]
    private array $staticRoutesIds;

    public function __construct(int $scenarioId, array $staticRoutesIds)
    {
        $this->scenarioId = $scenarioId;
        $this->staticRoutesIds = $staticRoutesIds;
    }

    public function setScenarioId(int $scenarioId): void
    {
        $this->scenarioId = $scenarioId;
    }

    public function getScenarioId(): int
    {
        return $this->scenarioId;
    }

    public function setStaticRoutesIds(array $staticRoutesIds): void
    {
        $this->staticRoutesIds = $staticRoutesIds;
    }

    public function getStaticRoutesIds(): array
    {
        return $this->staticRoutesIds;
    }
}
