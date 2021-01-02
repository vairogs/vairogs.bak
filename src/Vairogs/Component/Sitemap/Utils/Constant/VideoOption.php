<?php declare(strict_types = 1);

namespace Vairogs\Component\Sitemap\Utils\Constant;

use ReflectionException;
use Vairogs\Component\Utils\Helper\Iter;
use Vairogs\Component\Utils\Helper\Php;

final class VideoOption
{
    /**
     * @var string
     */
    public const RESTRICTION_DENY = 'deny';
    /**
     * @var string
     */
    public const RESTRICTION_ALLOW = 'allow';
    /**
     * @var string
     */
    public const PLATFORM_TV = 'tv';
    /**
     * @var string
     */
    public const PLATFORM_MOBILE = 'mobile';
    /**
     * @var string
     */
    public const PLATFORM_WEB = 'web';
    /**
     * @var string
     */
    public const OPTION_YES = 'yes';
    /**
     * @var string
     */
    public const OPTION_NO = 'no';

    /**
     * @return array
     * @throws ReflectionException
     */
    public static function getRestrictions(): array
    {
        return Iter::arrayValuesFiltered(Php::getClassConstants(self::class), 'RESTRICTION_');
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    public static function getPlatforms(): array
    {
        return Iter::arrayValuesFiltered(Php::getClassConstants(self::class), 'PLATFORM_');
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    public static function getOptions(): array
    {
        return Iter::arrayValuesFiltered(Php::getClassConstants(self::class), 'OPTION_');
    }
}
