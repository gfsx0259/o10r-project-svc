<?php

namespace App\Module\Dummy\Callback;

use App\Entity\Gateway\Callback;
use App\Module\Dummy\ModeManager;
use App\Repository\ScenarioRepository;

/**
 * Resolve callback collection for current state.
 * Detect stub collection by route id and find most suitable from it
 */
final readonly class CallbackCollectionProvider
{
    public function __construct(
        private ScenarioRepository $scenarioRepository,
        private ModeManager $modeManager,
    ) {}

    public function provide(int $routeId): array
    {
        $scenarios = $this->scenarioRepository->findByRoute($routeId);
        $currentScenario = $scenarios[0];
        $mode = $this->modeManager->get();

        if ($mode) {
            foreach ($scenarios as $scenario) {
                if ($scenario->getId() === $mode->getScenarioId()) {
                    $currentScenario = $scenario;
                    break;
                }
            }
        }

        return $currentScenario->getCallbacks()
            ->map(fn (Callback $cb) => json_decode($cb->getBody(), true))
            ->getValues();
    }
}
