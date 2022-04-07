<?php declare(strict_types = 1);

namespace Vairogs\Cache\Utils\Adapter;

use Psr\Cache\CacheItemPoolInterface;
use Vairogs\Extra\Constants\Status;

interface Adapter
{
    final public const DEFAULT_LIFETIME = Status::ZERO;

    public function getAdapter(): CacheItemPoolInterface;
}
