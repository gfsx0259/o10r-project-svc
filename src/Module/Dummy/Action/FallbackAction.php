<?php

declare(strict_types=1);

namespace App\Module\Dummy\Action;

use App\Module\Dummy\Collection\ArrayCollection;
use App\Module\Dummy\State;

/**
 * Use this action, if we have no ability to use same flag for init and complete request, just use payment id
 */
readonly class FallbackAction implements ActionInterface
{
    public function resolveAcceptedKey(ArrayCollection $callback, State $state): ?string
    {
        if (
            $callback->get('acs.iframe.body.threeDSMethodData') ||
            $callback->get('acs.redirect.body.threeDSSessionData')
        ) {
            return $state->getPaymentId();
        }

        return null;
    }

    public function resolveCompletedKey(ArrayCollection $completePayload): ?string
    {
        return $completePayload->get('general.payment_id');
    }
}
