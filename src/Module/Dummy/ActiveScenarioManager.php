<?php

namespace App\Module\Dummy;

use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ActiveScenarioManager
{
    private const CACHE_KEY = 'dummy_active_scenario';

    public function __construct(
        private CacheInterface $cache,
        private SerializerInterface $serializer,
    ) {}

    public function set(int $scenarioId, array $staticRoutesIds): void
    {
        $activeScenario = new ActiveScenario($scenarioId, $staticRoutesIds);

        $this->cache->set(self::CACHE_KEY, $this->serializer->serialize($activeScenario, 'json'), 3600);
    }

    public function get(): ?ActiveScenario
    {
        $activeScenario = $this->cache->get(self::CACHE_KEY);

        if ($activeScenario) {
            return $this->serializer->deserialize($activeScenario, ActiveScenario::class, 'json');
        }

        return null;
    }
}
