<?php

namespace App\Module\Dummy\Action;

use App\Module\Dummy\Collection\ArrayCollection;

interface ActionInterface
{
    /**
     * Check scenario current callback, if action detected - register it and stop instead of going to next callback
     *
     * @param ArrayCollection $callback
     * @return string|null
     */
    public function resolveAcceptedKey(ArrayCollection $callback): ?string;

    /**
     * Check complete action request and find suitable action, if successfully - use them to complete previously registered action
     * and move cursor to next callback
     *
     * @param ArrayCollection $completePayload
     * @return string|null
     */
    public function resolveCompletedKey(ArrayCollection $completePayload): ?string;
}
