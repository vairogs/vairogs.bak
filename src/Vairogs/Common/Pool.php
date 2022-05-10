<?php declare(strict_types = 1);

namespace Vairogs\Common;

use BadMethodCallException;
use InvalidArgumentException;
use Psr\Cache\CacheItemPoolInterface;
use Vairogs\Common\Adapter\Adapter;
use function sprintf;

final class Pool
{
    /**
     * @throws BadMethodCallException
     * @throws InvalidArgumentException
     */
    public function createPool(string $class, array $adapters = []): array
    {
        $pool = [];

        foreach ($adapters as $adapter) {
            if (!$adapter instanceof Adapter && !$adapter instanceof CacheItemPoolInterface) {
                throw new InvalidArgumentException(message: sprintf('Adapter must implement %s or %s', Adapter::class, CacheItemPoolInterface::class));
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
