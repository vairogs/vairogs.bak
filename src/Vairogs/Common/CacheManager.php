<?php declare(strict_types = 1);

namespace Vairogs\Common;

use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\ChainAdapter;
use Symfony\Component\Cache\Exception\CacheException;
use Symfony\Component\Cache\PruneableInterface;
use Vairogs\Common\Adapter\Adapter;
use Vairogs\Common\Adapter\File;
use Vairogs\Extra\Constants\Definition;

use function time;

final class CacheManager
{
    private readonly ArrayAdapter|ChainAdapter $adapter;

    /** @throws CacheException */
    public function __construct(private readonly int $defaultLifetime = Definition::DEFAULT_LIFETIME, private readonly bool $useFile = true, ...$adapters)
    {
        $this->adapter = $this->getAdapter(adapters: $adapters);
    }

    public function get(string $key, ?int $expiredTime = null): mixed
    {
        $this->prune();
        $expiredTime ??= time();

        try {
            $cache = $this->adapter->getItem(key: $key);

            if ($cache->isHit()) {
                $item = $cache->get();

                if ($expiredTime >= $item['expires']) {
                    $this->delete(key: $key);
                } else {
                    return $item['value'];
                }
            }
        } catch (InvalidArgumentException) {
            // cache not found
        }

        return null;
    }

    public function set(string $key, mixed $value, ?int $expiresAfter = null): void
    {
        $this->prune();

        try {
            $cache = $this->adapter->getItem(key: $key);
            $cache->set(value: ['value' => $value, 'expires' => time() + ($expiresAfter ??= $this->defaultLifetime)]);
            $cache->expiresAfter(time: $expiresAfter);

            $this->adapter->save(item: $cache);
        } catch (InvalidArgumentException) {
            // do not save if exception thrown
        }
    }

    public function delete(string $key): void
    {
        $this->prune();

        try {
            $this->adapter->delete(key: $key);
        } catch (InvalidArgumentException) {
            // key not found
        }
    }

    /** @throws CacheException */
    private function getAdapter(array $adapters): ArrayAdapter|ChainAdapter
    {
        if ([] === $adapters && $this->useFile) {
            $adapters[] = new File();
        }

        if ([] === $pool = $this->createPool(adapters: $adapters)) {
            return new ArrayAdapter(defaultLifetime: $this->defaultLifetime);
        }

        return new ChainAdapter(adapters: $pool, defaultLifetime: $this->defaultLifetime);
    }

    private function prune(): void
    {
        if ($this->adapter instanceof PruneableInterface) {
            $this->adapter->prune();
        }
    }

    /** @throws CacheException */
    private function createPool(array $adapters = []): array
    {
        $pool = [];

        foreach ($adapters as $adapter) {
            if (!$adapter instanceof Adapter && !$adapter instanceof CacheItemPoolInterface) {
                continue;
            }

            $pool[] = match (true) {
                $adapter instanceof Adapter => $adapter->getAdapter(defaultLifetime: $this->defaultLifetime),
                default => $adapter
            };
        }

        if ([] === $pool && $this->useFile) {
            $pool[] = (new File())->getAdapter(defaultLifetime: $this->defaultLifetime);
        }

        return $pool;
    }
}
