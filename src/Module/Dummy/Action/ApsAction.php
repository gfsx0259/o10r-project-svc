<?php

declare(strict_types=1);

namespace App\Module\Dummy\Action;

use App\Module\Dummy\Collection\ArrayCollection;

/**
 *  APS redirect action. Use `uniqueKey` query param to identify action.
 *  Flow:
 *  1. Generate link with {{APS_URL}} placeholder in your callback. Link will contain `uniqueKey` query param.
 *  `uniqueKey` is uniq for each callback in scenario (hash from payment id and cursor)
 *  2. Action register
 *  3. PP open APS url with `uniqueKey` param
 *  4. APS UI sends complete request to this dummy with `uniqueKey` param
 *  5. Dummy completes action
 *  6. PP fetch next status
 */
class ApsAction implements ActionInterface
{
    public function resolveAcceptedKey(ArrayCollection $callback): ?string
    {
        if (!$apsUrl = $callback->get('return_url.url')) {
            return null;
        }

        return $this->parseUniqueKey($apsUrl);
    }

    public function resolveCompletedKey(ArrayCollection $completePayload): ?string
    {
        return $completePayload->get('uniqueKey');
    }

    private function parseUniqueKey(string $url): string
    {
        $params = [];

        parse_str(parse_url($url, PHP_URL_QUERY), $params);

        return $params['uniqueKey'];
    }
}
