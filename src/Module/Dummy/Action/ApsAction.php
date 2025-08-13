<?php

declare(strict_types=1);

namespace App\Module\Dummy\Action;

use App\Module\Dummy\ActionException;
use App\Module\Dummy\Collection\ArrayCollection;

/**
 * APS redirect action. Use `uniqueKey` query param to identify action.
 * Flow:
 * 1. Generate link with {{APS_URL}} placeholder in your callback. Link will contain `uniqueKey` query param.
 * `uniqueKey` is uniq for each callback in scenario (hash from payment id and cursor)
 * 2. Action register
 * 3. PP open APS url with `uniqueKey` param
 * 4. APS UI sends complete request to this dummy with `uniqueKey` param
 * 5. Dummy completes action
 * 6. PP fetch next status
 */
class ApsAction extends AbstractAction
{
    public const string ACTION_KEY_NAME = 'uniqueKey';

    public function getActionKey(?ArrayCollection $completeRequest = null): string
    {
        if ($completeRequest && $completeRequest->get($this->getActionKeyName())) {
            return $completeRequest->get($this->getActionKeyName());
        } else {
            $redirectUrl = $this->callback->get('return_url.url');

            if ($actionKey = $this->parseUniqueKey($redirectUrl)) {
                return $actionKey;
            }

            throw new ActionException(sprintf('Can not find %s in %s', $this->getActionKeyName(), $redirectUrl));
        }
    }

    private function parseUniqueKey(string $url): string
    {
        $params = [];
        parse_str(parse_url($url, PHP_URL_QUERY), $params);
        return $params['uniqueKey'];
    }

    public function getActionKeyName(): string
    {
        return self::ACTION_KEY_NAME;
    }
}
