<?php

namespace App\Module\Dummy\Callback;

use App\Module\Dummy\Action\ActionPicker;
use App\Module\Dummy\Collection\ArrayCollection;
use App\Module\Dummy\State;
use App\Module\Dummy\StateManager;

/**
 * Process callback entity:
 * * Find current callback
 * * Apply overrides to callback
 * * Move cursor or register action
 * * Save state instance to storage
 * * Return callback
 */
final readonly class CallbackProcessor
{
    public function __construct(
        private StateManager $stateManager,
        private OverrideProcessor $overrideProcessor,
        private ActionPicker $actionPicker,
    ) {}

    public function process(State $state): ArrayCollection
    {
        $collection = new ArrayCollection($state->findCurrentCallback());
        $collection = $this->overrideProcessor->process($collection, $state);

        if ($action = $this->actionPicker->pickAccepted($collection)) {
            $actionKey = $action->resolveAcceptedKey($collection);

            if ($state->isActionCompleted($actionKey)) {
                $this->next($state);
            } else {
                $state->registerAction($actionKey);
            }
        } else {
            $this->next($state);
        }

        $this->stateManager->save($state);

        return $collection;
    }

    private function next(State $state): void
    {
        if ($state->getCursor() < count($state->getCallbacks()) - 1) {
            $state->next();
        }
    }
}
