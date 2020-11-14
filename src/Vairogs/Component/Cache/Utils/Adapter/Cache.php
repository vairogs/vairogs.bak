<?php declare(strict_types = 1);

namespace Vairogs\Component\Cache\Utils\Adapter;

use Psr\Cache\CacheItemPoolInterface;

interface Cache
{
    public function getAdapter(): CacheItemPoolInterface;
}
