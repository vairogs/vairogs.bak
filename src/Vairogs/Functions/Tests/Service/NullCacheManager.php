<?php declare(strict_types = 1);

namespace Vairogs\Functions\Tests\Service;

use Vairogs\Core\Contracts\CacheManagerInterface;

class NullCacheManager implements CacheManagerInterface
{
    public function get(string $key, ?int $expiredTime = null): null
    {
        return null;
    }

    public function set(string $key, mixed $value, ?int $expiresAfter = null): void
    {
    }

    public function delete(string $key): null
    {
        return null;
    }
}
