<?php

namespace App\Module\Dummy;

use Exception;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

final readonly class StateManager
{
    public function __construct(
        private CacheInterface $cache,
        private SerializerInterface $serializer,
        private LoggerInterface $logger,
    ) {}

    /**
     * @throws InvalidArgumentException
     * @throws ExceptionInterface
     */
    public function get(string $paymentId): ?State
    {
        if (!$state = $this->cache->get($this->hashKey($paymentId))) {
            return null;
        }

        return $this->serializer->deserialize($state, State::class, 'json');
    }

    public function restore(string $uniqueKey): ?State
    {
        if (!$intermediateState = $this->cache->get($uniqueKey)) {
            return null;
        }

        return $this->get($intermediateState);
    }

    public function save(State $state): bool
    {
        $paymentId = $state->getInitialRequest()->get('general.payment_id');

        $key = $this->hashKey($paymentId);
        $value = $this->serializer->serialize($state, 'json');

        try {
            $isSaved = $this->cache->set($key, $value);

            if (!$isSaved) {
                $this->logger->error('Can not save state: ' . $key, ['value' => $value]);
            }
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            return false;
        }

        return true;
    }

    public function generateAccessKey(State $state): string
    {
        $uniqueKey = md5($state->getPaymentId() . $state->getCursor());

        $this->cache->set($uniqueKey, $state->getPaymentId());

        return $uniqueKey;
    }

    private function hashKey(string $key): string
    {
        return md5(preg_replace('/[^a-zA-Z0-9_:.-]/', '_', $key));
    }
}
