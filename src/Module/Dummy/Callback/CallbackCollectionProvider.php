<?php

namespace App\Module\Dummy\Callback;

use App\Entity\Gateway\Callback;
use App\Module\Dummy\Specification\SpecificationEntityCollectionResolver;
use App\Repository\ScenarioRepository;

/**
 * Resolve callback collection for current state.
 * Detect stub collection by route id and find most suitable from it
 */
final readonly class CallbackCollectionProvider
{
    public function __construct(
        private ScenarioRepository $scenarioRepository,
        private SpecificationEntityCollectionResolver $entityCollectionResolver,
    ) {}

    public function provide(int $routeId, array $initialRequest): array
    {
        $scenarios = $this->scenarioRepository->findByRoute($routeId);
        $currentScenario = $this->entityCollectionResolver->resolveMostPriority(
            $initialRequest,
            $scenarios
        );

        return $currentScenario->getCallbacks()
            ->map(fn (Callback $cb) => json_decode($cb->getBody(), true))
            ->getValues();
    }
}
