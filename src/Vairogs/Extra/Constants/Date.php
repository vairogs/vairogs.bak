<?php declare(strict_types = 1);

namespace Vairogs\Extra\Constants;

final class Date
{
    public const FORMAT = 'd-m-Y H:i:s';
    public const FORMAT_TS = 'D M d Y H:i:s T';
    public const HOUR = 60 * self::MIN;
    public const MIN = 60 * self::SEC;
    public const MS = 1;
    public const SEC = 1000 * self::MS;
}
