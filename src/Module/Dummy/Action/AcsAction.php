<?php

declare(strict_types=1);

namespace App\Module\Dummy\Action;

use App\Module\Dummy\Collection\ArrayCollection;

/**
 * ACS redirect action. Use `MD` param to identify action.
 * Flow:
 * 1. Generate link with {{ACS_URL}} and {{MD}} placeholder in your callback. MD param will contain `uniqueKey`.
 * `uniqueKey` is uniq for each callback in scenario (hash from payment id and cursor)
 * 2. Action register
 * 3. PP open ACS url with passing `MD` param in body
 * 4. ACS UI sends complete request to PP with `MD` param
 * 5. PP sends request to dummy to completes action
 * 6. PP fetch next status
 *
 * To test: curl -vvv -X POST https://project.o10r.io/proxy/acs   -H "Content-Type: application/json" -d '{"md": "b428cbb02358afc32cf32f9bdb725a51"}'
 */
class AcsAction implements ActionInterface
{
    public function resolveAcceptedKey(ArrayCollection $callback): ?string
    {
        return $callback->get('acs.md');
    }

    public function resolveCompletedKey(ArrayCollection $completePayload): ?string
    {
        return $completePayload->get('md');
    }
}
