<?php declare(strict_types = 1);

namespace Vairogs\Core\Contracts;

interface CacheManagerInterface
{
    public function get(string $key, ?int $expiredTime = null);

    public function set(string $key, mixed $value, ?int $expiresAfter = null);

    public function delete(string $key);
}
