<?php declare(strict_types = 1);

namespace Vairogs\Component\Sitemap\Utils\Constant;

use ReflectionException;
use Vairogs\Component\Utils\Helper\Php;

final class ChangeFrequency
{
    public const ALWAYS = 'always';
    public const HOURLY = 'hourly';
    public const DAILY = 'daily';
    public const WEEKLY = 'weekly';
    public const MONTHLY = 'monthly';
    public const YEARLY = 'yearly';
    public const NEVER = 'never';
    public const EMPTY = null;

    /**
     * @return array
     * @throws ReflectionException
     */
    public static function getChangeFrequencies(): array
    {
        return Php::getClassConstantsValues(self::class);
    }
}
