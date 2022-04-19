<?php declare(strict_types = 1);

namespace Vairogs\Cache\Adapter;

use Predis\ClientInterface;
use Psr\Cache\CacheItemPoolInterface;
use Redis;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Vairogs\Core\Vairogs;
use Vairogs\Utils\Helper\Composer;
use function implode;
use function sprintf;

abstract class AbstractRedisAdapter implements Adapter
{
    protected string $class;
    protected array $packages;

    public function __construct(protected readonly Redis|ClientInterface $client, protected readonly string $namespace = Vairogs::VAIROGS)
    {
        if (!Composer::isInstalled(packages: $this->packages, includeDevRequirements: false)) {
            throw new InvalidConfigurationException(message: sprintf('In order to use %s, package(s)/extension(s) "%s" must be installed', $this->class, implode(separator: ',', array: $this->packages)));
        }
    }

    public function getAdapter(): CacheItemPoolInterface
    {
        return new RedisAdapter(redis: $this->client, namespace: $this->namespace, defaultLifetime: Adapter::DEFAULT_LIFETIME);
    }
}
