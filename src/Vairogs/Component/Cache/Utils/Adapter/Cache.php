<?php declare(strict_types = 1);

namespace Vairogs\Component\Cache\Utils\Adapter;

use Psr\Cache\CacheItemPoolInterface;

interface Cache
{
    public const DEFAULT_LIFETIME = 0;

    public function getAdapter(): CacheItemPoolInterface;
}
