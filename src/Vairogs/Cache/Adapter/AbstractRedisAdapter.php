<?php declare(strict_types = 1);

namespace Vairogs\Cache\Adapter;

use Predis\ClientInterface;
use Psr\Cache\CacheItemPoolInterface;
use Redis;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Vairogs\Core\Vairogs;
use Vairogs\Extra\Constants\Definition;

abstract class AbstractRedisAdapter extends AbstractAdapter
{
    public function __construct(protected readonly Redis|ClientInterface $client, protected readonly string $namespace = Vairogs::VAIROGS)
    {
        $this->checkRequirements();
    }

    public function getAdapter(int $defaultLifetime = Definition::DEFAULT_LIFETIME): CacheItemPoolInterface
    {
        return new RedisAdapter(redis: $this->client, namespace: $this->namespace, defaultLifetime: $defaultLifetime);
    }
}
