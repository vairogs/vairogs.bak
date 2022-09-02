<?php declare(strict_types = 1);

namespace Vairogs\Cache\Adapter;

use Psr\Cache\CacheItemPoolInterface;
use Vairogs\Extra\Constants\Definition;

interface Adapter
{
    public function getAdapter(int $defaultLifetime = Definition::DEFAULT_LIFETIME): CacheItemPoolInterface;
}
