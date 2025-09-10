<?php

namespace App\Module\Dummy;

use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ModeManager
{
    private const CACHE_KEY = 'dummy_mode';
    private ?Mode $mode = null;

    public function __construct(
        private CacheInterface $cache,
        private SerializerInterface $serializer,
    ) {}

    public function set(int $scenarioId, array $staticRoutesIds): void
    {
        $mode = new Mode($scenarioId, $staticRoutesIds);

        $this->store($mode);
    }

    public function get(): ?Mode
    {
        if ($this->mode) {
            return $this->mode;
        }

        return $this->restore();
    }

    private function store(Mode $mode)
    {
        $this->mode = $mode;

        $this->cache->set(self::CACHE_KEY, $this->serializer->serialize($mode, 'json'), 31536000);
    }

    private function restore(): ?Mode
    {
        if ($mode = $this->cache->get(self::CACHE_KEY)) {
            $this->mode = $this->serializer->deserialize($mode, Mode::class, 'json');
        }

        return $this->mode;
    }
}
