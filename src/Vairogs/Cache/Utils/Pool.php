<?php declare(strict_types = 1);

namespace Vairogs\Cache\Utils;

use BadMethodCallException;
use InvalidArgumentException;
use Psr\Cache\CacheItemPoolInterface;
use Vairogs\Cache\Utils\Adapter\Cache;
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
            if (!$adapter instanceof Cache && !$adapter instanceof CacheItemPoolInterface) {
                throw new InvalidArgumentException(message: sprintf('Adapter %s must implement %s or %s', $adapter::class, Cache::class, CacheItemPoolInterface::class));
            }

            if ($adapter instanceof Cache) {
                $pool[] = $adapter->getAdapter();
            } else {
                $pool[] = $adapter;
            }
        }

        if ([] === $pool) {
            throw new BadMethodCallException(message: sprintf('At least one provider must be provided in order to use %s', $class));
        }

        return $pool;
    }
}
