<?php declare(strict_types = 1);

namespace Vairogs\Cache\Utils\Adapter;

use Psr\Cache\CacheItemPoolInterface;

interface Adapter
{
    public const DEFAULT_LIFETIME = 0;

    public function getAdapter(): CacheItemPoolInterface;
}
