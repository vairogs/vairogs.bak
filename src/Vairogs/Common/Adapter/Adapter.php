<?php declare(strict_types = 1);

namespace Vairogs\Common\Adapter;

use Psr\Cache\CacheItemPoolInterface;

interface Adapter
{
    public function getAdapter(): CacheItemPoolInterface;
}
