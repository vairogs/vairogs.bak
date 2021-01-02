<?php declare(strict_types = 1);

namespace Vairogs\Component\Cache\Utils;

use ReflectionClass;

final class Strategy
{
    /**
     * @var string
     */
    public const GET = 'GET';
    /**
     * @var string
     */
    public const POST = 'POST';
    /**
     * @var string
     */
    public const USER = 'USER';
    /**
     * @var string
     */
    public const MIXED = 'MIXED';
    /**
     * @var string
     */
    public const ALL = 'ALL';

    /**
     * @return array
     */
    public function getStrategies(): array
    {
        return (new ReflectionClass(self::class))->getConstants();
    }
}
