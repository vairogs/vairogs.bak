<?php declare(strict_types = 1);

namespace Vairogs\Component\Cache\Utils;

use ReflectionException;
use Vairogs\Component\Utils\Helper\Php;

final class Strategy
{
    public const GET = 'GET';
    public const POST = 'POST';
    public const USER = 'USER';
    public const MIXED = 'MIXED';
    public const ALL = 'ALL';

    /**
     * @throws ReflectionException
     */
    public function getStrategies(): array
    {
        return Php::getClassConstants(self::class);
    }
}
