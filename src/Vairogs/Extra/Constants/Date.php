<?php declare(strict_types = 1);

namespace Vairogs\Extra\Constants;

final class Date
{
    final public const FORMAT = 'd-m-Y H:i:s';
    final public const FORMAT_TS = 'D M d Y H:i:s T';
    final public const HOUR = 60 * self::MIN;
    final public const MIN = 60 * self::SEC;
    final public const SEC = 1000;
}
