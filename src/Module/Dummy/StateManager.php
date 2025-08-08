<?php

namespace App\Module\Dummy;

use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

final readonly class StateManager
{
    public function __construct(
        private CacheInterface $cache,
    ) {}

    /**
     * @throws InvalidArgumentException
     */
    public function get(string $key): ?State
    {
        $state = $this->cache->get($key);

        if ($state instanceof State) {
            return $state;
        } else {
            return null;
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    public function generateAccessKey(State $state): string
    {
        return md5($state->getPaymentId() . $state->getCursor());
    }

    public function save(State $state): bool
    {
        $paymentId = $state->getInitialRequest()->get('general.payment_id');

        try {
            $this->cache->set(
                $paymentId,
                $state
            );
        } catch (InvalidArgumentException) {
            return false;
        }

        return true;
    }
}
