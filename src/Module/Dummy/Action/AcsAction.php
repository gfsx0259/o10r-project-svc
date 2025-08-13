<?php

declare(strict_types=1);

namespace App\Module\Dummy\Action;

use App\Module\Dummy\ActionException;
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
 */
class AcsAction extends AbstractAction
{
    public const string ACTION_KEY_NAME = 'md';

    public function getActionKey(?ArrayCollection $completeRequest = null): string
    {
        if ($completeRequest && $completeRequest->get($this->getActionKeyName())) {
            return $completeRequest->get($this->getActionKeyName());
        } else {
            if ($actionKey = $this->callback->get('acs.md')) {
                return $actionKey;
            }

            throw new ActionException(sprintf('Can not find %s', $this->getActionKeyName()));
        }
    }

    public function getActionKeyName(): string
    {
        return self::ACTION_KEY_NAME;
    }
}
