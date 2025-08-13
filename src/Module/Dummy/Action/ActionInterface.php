<?php

namespace App\Module\Dummy\Action;

use App\Module\Dummy\Collection\ArrayCollection;

interface ActionInterface
{
    public function resolveAcceptedKey(ArrayCollection $callback): ?string;

    public function resolveCompletedKey(ArrayCollection $completePayload): ?string;
}
