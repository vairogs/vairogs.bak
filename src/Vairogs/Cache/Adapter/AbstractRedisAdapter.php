<?php declare(strict_types = 1);

namespace Vairogs\Cache\Adapter;

use Predis\ClientInterface;
use Psr\Cache\CacheItemPoolInterface;
use Redis;
use Symfony\Component\Cache\Adapter\RedisTagAwareAdapter;
use Vairogs\Core\Vairogs;
use Vairogs\Extra\Constants\Definition;

abstract class AbstractRedisAdapter extends AbstractAdapter
{
    public function __construct(protected readonly Redis|ClientInterface $client, protected readonly string $namespace = Vairogs::VAIROGS, bool $incDevReq = false)
    {
        $this->checkRequirements(incDevReq: $incDevReq);
    }

    public function getAdapter(int $defaultLifetime = Definition::DEFAULT_LIFETIME): CacheItemPoolInterface
    {
        return new RedisTagAwareAdapter(redis: $this->client, namespace: $this->namespace, defaultLifetime: $defaultLifetime);
    }
}
