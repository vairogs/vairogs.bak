<?php declare(strict_types = 1);

namespace Vairogs\Cache\Utils\Adapter;

use Predis\ClientInterface;
use Psr\Cache\CacheItemPoolInterface;
use Snc\RedisBundle\SncRedisBundle;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Vairogs\Utils\Helper\Php;
use Vairogs\Utils\Vairogs;
use function sprintf;

class Predis implements Adapter
{
    public function __construct(private readonly ClientInterface $client, private readonly string $namespace = Vairogs::VAIROGS)
    {
        if (!Php::exists(class: SncRedisBundle::class) || !Php::exists(class: ClientInterface::class)) {
            throw new InvalidConfigurationException(message: sprintf('Packages %s and %s must be installed in order to use %s', 'snc/redis-bundle', 'predis/predis', self::class));
        }
    }

    public function getAdapter(): CacheItemPoolInterface
    {
        return new RedisAdapter(redis: $this->client, namespace: $this->namespace, defaultLifetime: Adapter::DEFAULT_LIFETIME);
    }
}
