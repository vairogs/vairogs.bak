<?php declare(strict_types = 1);

namespace Vairogs\Component\Sitemap\Utils\Constant;

use ReflectionException;
use Vairogs\Component\Utils\Helper\Iter;
use Vairogs\Component\Utils\Helper\Php;

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
     * @return array
     * @throws ReflectionException
     */
    public static function getRestrictions(): array
    {
        return Iter::arrayValuesFiltered(Php::getClassConstants(__CLASS__), 'RESTRICTION_');
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    public static function getPlatforms(): array
    {
        return Iter::arrayValuesFiltered(Php::getClassConstants(__CLASS__), 'PLATFORM_');
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    public static function getOptions(): array
    {
        return Iter::arrayValuesFiltered(Php::getClassConstants(__CLASS__), 'OPTION_');
    }
}
