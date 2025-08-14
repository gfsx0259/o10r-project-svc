<?php

declare(strict_types=1);

use App\Module\Dummy\Collection\ArrayCollectionCast;
use Predis\Client;
use Predis\ClientInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Yiisoft\Cache\Redis\RedisCache;

return [
    Yiisoft\Cache\CacheInterface::class => Yiisoft\Cache\Cache::class,
    Psr\SimpleCache\CacheInterface::class => RedisCache::class,
    ClientInterface::class => [
        'class' => Client::class,
        '__construct()' => [
            'parameters' => $_ENV['DUMMY_REDIS_DSN']
        ],
    ],
    SerializerInterface::class => function () {
        return new Serializer(
            [new ArrayCollectionCast(), new ObjectNormalizer()],
            [new JsonEncoder()],
        );
    },
];
