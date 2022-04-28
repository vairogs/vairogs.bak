<?php declare(strict_types = 1);

namespace Vairogs\Common\Adapter;

use Predis\ClientInterface;
use Psr\Cache\CacheItemPoolInterface;
use Redis;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Vairogs\Common\Common;
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
        if (!Composer::isInstalled(packages: $this->packages, incDevReq: false)) {
            throw new InvalidConfigurationException(message: sprintf('In order to use %s, package(s)/extension(s) "%s" must be installed', $this->class, implode(separator: ',', array: $this->packages)));
        }
    }

    public function getAdapter(): CacheItemPoolInterface
    {
        return new RedisAdapter(redis: $this->client, namespace: $this->namespace, defaultLifetime: Common::DEFAULT_LIFETIME);
    }
}
