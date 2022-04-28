<?php declare(strict_types = 1);

namespace Vairogs\Common;

use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\ChainAdapter;

trait Cache
{
    /**
     * @throws InvalidArgumentException
     */
    private function getCache(ChainAdapter $adapter, string $key): mixed
    {
        $cache = $adapter->getItem(key: $key);

        if ($cache->isHit()) {
            return $cache->get();
        }

        return null;
    }

    /**
     * @throws InvalidArgumentException
     */
    private function setCache(ChainAdapter $adapter, string $key, mixed $value, int $expiresAfter): void
    {
        $cache = $adapter->getItem(key: $key);
        $cache->set(value: $value);
        $cache->expiresAfter(time: $expiresAfter);

        $adapter->save(item: $cache);
    }
}
