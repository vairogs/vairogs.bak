<?php declare(strict_types = 1);

namespace Vairogs\Component\Sitemap\Utils\Constant;

use ReflectionException;
use Vairogs\Component\Utils\Helper\Php;

final class ChangeFrequency
{
    /**
     * @var string
     */
    public const ALWAYS = 'always';

    /**
     * @var string
     */
    public const HOURLY = 'hourly';

    /**
     * @var string
     */
    public const DAILY = 'daily';

    /**
     * @var string
     */
    public const WEEKLY = 'weekly';

    /**
     * @var string
     */
    public const MONTHLY = 'monthly';

    /**
     * @var string
     */
    public const YEARLY = 'yearly';

    /**
     * @var string
     */
    public const NEVER = 'never';

    /**
     * @var null
     */
    public const EMPTY = null;

    /**
     * @return array
     * @throws ReflectionException
     */
    public static function getChangeFrequencies(): array
    {
        return Php::getClassConstantsValues(__CLASS__);
    }
}
