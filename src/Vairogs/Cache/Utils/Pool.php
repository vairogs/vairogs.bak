<?php declare(strict_types = 1);

namespace Vairogs\Cache\Utils;

use BadMethodCallException;
use InvalidArgumentException;
use Psr\Cache\CacheItemPoolInterface;
use Vairogs\Cache\Utils\Adapter\Adapter;
use function sprintf;

class Pool
{
    /**
     * @throws BadMethodCallException
     * @throws InvalidArgumentException
     */
    public static function createPool(string $class, array $adapters = []): array
    {
        $pool = [];

        foreach ($adapters as $adapter) {
            if (!$adapter instanceof Adapter && !$adapter instanceof CacheItemPoolInterface) {
                throw new InvalidArgumentException(message: sprintf('Adapter %s must implement %s or %s', $adapter::class, Adapter::class, CacheItemPoolInterface::class));
            }

            $pool[] = match (true) {
                $adapter instanceof Adapter => $adapter->getAdapter(),
                default => $adapter
            };
        }

        if ([] === $pool) {
            throw new BadMethodCallException(message: sprintf('At least one provider must be provided in order to use %s', $class));
        }

        return $pool;
    }
}
