<?php

namespace App\Module\Dummy\Callback;

use App\Module\Dummy\Action\AbstractAction;
use App\Module\Dummy\ActionFactory;
use App\Module\Dummy\Collection\ArrayCollection;
use App\Module\Dummy\State;
use App\Module\Dummy\StateManager;

/**
 * Process callback entity:
 * * Apply overrides to callback
 * * Move cursor or register action
 * * Save state instance to storage
 */
final readonly class CallbackProcessor
{
    public function __construct(
        private ActionFactory $actionFactory,
        private StateManager $stateManager,
        private OverrideProcessor $overrideProcessor,
    ) {}

    public function process(State $state): ArrayCollection
    {
        $collection = new ArrayCollection($state->findCurrentCallback());
        $collection = $this->overrideProcessor->process($collection, $state);

        if ($action = $this->actionFactory->make($collection, $state)) {
            $this->applyAction($action, $state);
        } else {
            $this->next($state);
        }

        $this->stateManager->save($state);

        return $collection;
    }

    private function applyAction(AbstractAction $action, State $state): void
    {
        $action->isCompleted()
            ? $this->next($state)
            : $action->register();
    }

    private function next(State $state): void
    {
        if ($state->getCursor() < count($state->getCallbacks()) - 1) {
            $state->next();
        }
    }
}
