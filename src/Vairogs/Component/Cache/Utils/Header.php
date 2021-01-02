<?php declare(strict_types = 1);

namespace Vairogs\Component\Cache\Utils;

final class Header
{
    /**
     * @var string
     */
    public const CACHE_VAR = 'Vairogs-Cache';
    /**
     * @var string
     */
    public const INVALIDATE = 'invalidate';
    /**
     * @var string
     */
    public const SKIP = 'skip';
}
