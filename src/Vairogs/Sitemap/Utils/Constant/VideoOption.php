<?php declare(strict_types = 1);

namespace Vairogs\Sitemap\Utils\Constant;

use ReflectionException;
use Vairogs\Utils\Helper\Iteration;
use Vairogs\Utils\Helper\Php;

final class VideoOption
{
    public const RESTRICTION_DENY = 'deny';
    public const RESTRICTION_ALLOW = 'allow';
    public const PLATFORM_TV = 'tv';
    public const PLATFORM_MOBILE = 'mobile';
    public const PLATFORM_WEB = 'web';
    public const OPTION_YES = 'yes';
    public const OPTION_NO = 'no';

    /**
     * @throws ReflectionException
     */
    public static function getRestrictions(): array
    {
        return Iteration::arrayValuesFiltered(input: Php::getClassConstants(class: self::class), with: 'RESTRICTION_');
    }

    /**
     * @throws ReflectionException
     */
    public static function getPlatforms(): array
    {
        return Iteration::arrayValuesFiltered(input: Php::getClassConstants(class: self::class), with: 'PLATFORM_');
    }

    /**
     * @throws ReflectionException
     */
    public static function getOptions(): array
    {
        return Iteration::arrayValuesFiltered(input: Php::getClassConstants(class: self::class), with: 'OPTION_');
    }
}
