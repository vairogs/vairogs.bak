<?php declare(strict_types = 1);

namespace Vairogs\Sitemap\Utils\Constant;

use RuntimeException;
use Vairogs\Utils\Helper\Iteration;
use Vairogs\Utils\Helper\Php;

final class VideoOption
{
    final public const RESTRICTION_DENY = 'deny';
    final public const RESTRICTION_ALLOW = 'allow';
    final public const PLATFORM_TV = 'tv';
    final public const PLATFORM_MOBILE = 'mobile';
    final public const PLATFORM_WEB = 'web';
    final public const OPTION_YES = 'yes';
    final public const OPTION_NO = 'no';

    /** @throws RuntimeException */
    public function getRestrictions(): array
    {
        return (new Iteration())->arrayValuesFiltered(input: (new Php())->getClassConstants(class: self::class), with: 'RESTRICTION_');
    }

    /** @throws RuntimeException */
    public function getPlatforms(): array
    {
        return (new Iteration())->arrayValuesFiltered(input: (new Php())->getClassConstants(class: self::class), with: 'PLATFORM_');
    }

    /** @throws RuntimeException */
    public function getOptions(): array
    {
        return (new Iteration())->arrayValuesFiltered(input: (new Php())->getClassConstants(class: self::class), with: 'OPTION_');
    }
}
