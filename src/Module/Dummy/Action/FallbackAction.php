<?php

declare(strict_types=1);

namespace App\Module\Dummy\Action;

use App\Module\Dummy\Collection\ArrayCollection;

/**
 * Use this action, if we have no ability to use same flag for init and complete request, just use payment id
 */
readonly class FallbackAction implements ActionInterface
{
    public function resolveAcceptedKey(ArrayCollection $callback): ?string
    {
        if (
            $callback->get('acs.iframe.body.threeDSMethodData') ||
            $callback->get('acs.redirect.body.threeDSSessionData')
        ) {
            return $callback->get('general.payment_id');
        }

        return null;
    }

    public function resolveCompletedKey(ArrayCollection $completePayload): ?string
    {
        return $completePayload->get('general.payment_id');
    }
}
