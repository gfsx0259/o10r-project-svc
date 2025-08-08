<?php

namespace App\Module\Dummy\Callback;

use App\Entity\Gateway\Callback;
use App\Module\Dummy\Specification\SpecificationEntityCollectionResolver;
use App\Module\Dummy\State;
use App\Repository\ScenarioRepository;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Resolve callback for current state:
 * * Detect current stub by route id restored from state
 * * Resolve stub callback using cursor from state
 */
final readonly class CallbackResolver
{
    public function __construct(
        private ScenarioRepository $scenarioRepository,
        private SpecificationEntityCollectionResolver $entityCollectionResolver,
    ) {}

    public function resolve(State $state): Callback
    {
        $callbacks = $this->getCallbacks($state);
        $callback = $callbacks->get($state->getCursor());

        return $callback
            ?: $callbacks->last();
    }

    public function getCallbacksCount(State $state): int
    {
        return count($this->getCallbacks($state));
    }

    private function getCallbacks(State $state): ArrayCollection
    {
        $scenarios = $this->scenarioRepository->findByRoute($state->getRouteId());
        $currentScenario = $this->entityCollectionResolver->resolveMostPriority(
            $state->getInitialRequest()->data,
            $scenarios
        );


        return $currentScenario->getCallbacks();
    }
}
