<?php

namespace App\Module\Dummy\Action;

use App\Module\Dummy\Collection\ArrayCollection;
use Yiisoft\Injector\Injector;

final readonly class ActionPicker
{
    /**
     * @param Injector $injector
     * @param array $actionClasses
     */
    public function __construct(
        private Injector $injector,
        private array $actionClasses,
    ) {}

    public function pickAccepted(ArrayCollection $callback): ?ActionInterface
    {
        foreach ($this->actionClasses as $actionClass) {
            /** @var ActionInterface $actionCandidate */
            $actionCandidate = $this->injector->make($actionClass);

            if ($actionCandidate->resolveAcceptedKey($callback)) {
                return $actionCandidate;
            }
        }

        return null;
    }

    public function pickCompleted(ArrayCollection $completePayload): ?ActionInterface
    {
        foreach ($this->actionClasses as $actionClass) {
            /** @var ActionInterface $actionCandidate */
            $actionCandidate = $this->injector->make($actionClass);

            if ($actionCandidate->resolveCompletedKey($completePayload)) {
                return $actionCandidate;
            }
        }

        return null;
    }
}
