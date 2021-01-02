<?php declare(strict_types = 1);

namespace Vairogs\Component\Cache\Utils;

use ReflectionClass;

final class Strategy
{
    public const GET = 'GET';
    public const POST = 'POST';
    public const USER = 'USER';
    public const MIXED = 'MIXED';
    public const ALL = 'ALL';

    /**
     * @return array
     */
    public function getStrategies(): array
    {
        return (new ReflectionClass(self::class))->getConstants();
    }
}
