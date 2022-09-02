<?php declare(strict_types = 1);

namespace Vairogs\Cache\Adapter;

final class PhpRedis extends AbstractRedisAdapter
{
    protected string $class = self::class;
    protected array $packages = ['redis'];
}
