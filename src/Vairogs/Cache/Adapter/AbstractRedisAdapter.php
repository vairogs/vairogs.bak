<?php declare(strict_types = 1);

namespace Vairogs\Cache\Adapter;

use Predis\ClientInterface;
use Psr\Cache\CacheItemPoolInterface;
use Redis;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Vairogs\Core\Vairogs;

abstract class AbstractRedisAdapter implements Adapter
{
    protected const MESSAGE = 'In order to use %s, package(s)/extension(s) "%s" must be installed';

    public function __construct(protected readonly Redis|ClientInterface $client, protected readonly string $namespace = Vairogs::VAIROGS)
    {
        if (null !== $message = $this->checkDeclaration()) {
            throw new InvalidConfigurationException(message: $message);
        }
    }

    public function getAdapter(): CacheItemPoolInterface
    {
        return new RedisAdapter(redis: $this->client, namespace: $this->namespace, defaultLifetime: Adapter::DEFAULT_LIFETIME);
    }

    abstract protected function checkDeclaration(): ?string;
}
