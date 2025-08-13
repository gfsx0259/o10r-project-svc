<?php

namespace App\Module\Dummy\Action;

use App\Module\Dummy\ActionException;
use App\Module\Dummy\Collection\ArrayCollection;
use App\Module\Dummy\State;

abstract class AbstractAction
{
    public function __construct(
        protected ArrayCollection $callback,
        protected State $state
    ) {}

    abstract public function getActionKeyName(): string;

    /**
     * Gets unique key for this action. This method is called twice: once to return the callback, and once to complete the action.
     *
     * @param ArrayCollection|null $completeRequest
     * @return string
     * @throws ActionException
     */
    abstract public function getActionKey(?ArrayCollection $completeRequest = null): string;

    public function register(): bool
    {
        if (!$this->isCompleted()) {
            $this->state->registerAction($this->getActionKey());

            return true;
        }

        return false;
    }

    public function isCompleted(): bool
    {
        return $this->state->isActionCompleted($this->getActionKey());
    }

    public function complete(ArrayCollection $completeRequest): void
    {
        $actionKey = $this->getActionKey($completeRequest);

        if (!$this->state->isActionCompleted($actionKey)) {
            $this->state->completeAction($actionKey);
            $this->state->next();
        }
    }
}
