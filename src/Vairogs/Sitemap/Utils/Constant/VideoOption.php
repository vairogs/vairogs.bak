<?php declare(strict_types = 1);

namespace Vairogs\Sitemap\Utils\Constant;

use RuntimeException;
use Vairogs\Extra\Constants\Status;
use Vairogs\Utils\Helper\Iteration;
use Vairogs\Utils\Helper\Php;

final class VideoOption
{
    public const RESTRICTION_DENY = 'deny';
    public const RESTRICTION_ALLOW = 'allow';
    public const PLATFORM_TV = 'tv';
    public const PLATFORM_MOBILE = 'mobile';
    public const PLATFORM_WEB = 'web';
    public const OPTION_YES = Status::YES;
    public const OPTION_NO = Status::NO;

    /**
     * @throws RuntimeException
     */
    public function getRestrictions(): array
    {
        return (new Iteration())->arrayValuesFiltered(input: (new Php())->getClassConstants(class: self::class), with: 'RESTRICTION_');
    }

    /**
     * @throws RuntimeException
     */
    public function getPlatforms(): array
    {
        return (new Iteration())->arrayValuesFiltered(input: (new Php())->getClassConstants(class: self::class), with: 'PLATFORM_');
    }

    /**
     * @throws RuntimeException
     */
    public function getOptions(): array
    {
        return (new Iteration())->arrayValuesFiltered(input: (new Php())->getClassConstants(class: self::class), with: 'OPTION_');
    }
}
