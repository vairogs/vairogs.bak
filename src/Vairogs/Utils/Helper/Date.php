<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use DateTime;
use Exception;
use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use Vairogs\Utils\Twig\Attribute;
use function array_merge;
use function floor;
use function gmdate;
use function round;
use function substr;
use function trim;

final class Date
{
    final public const FORMAT = 'd-m-Y H:i:s';
    final public const FORMAT_TS = 'D M d Y H:i:s T';
    final public const EXTRA_FORMATS = [
        self::FORMAT,
        self::FORMAT_TS,
    ];
    final public const SEC = 1000;
    final public const MIN = 60 * self::SEC;
    final public const HOUR = 60 * self::MIN;
    final public const TIME = [
        'hour' => self::HOUR,
        'minute' => self::MIN,
        'second' => self::SEC,
    ];

    #[Attribute\TwigFunction]
    public static function validateDate(string $date): bool
    {
        $date = Text::keepNumeric(string: $date);
        $day = (int) substr(string: $date, offset: 0, length: 2);
        $month = (int) substr(string: $date, offset: 2, length: 2);

        if (1 > $month || 12 < $month) {
            return false;
        }

        $daysInMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

        if (0 === (int) substr(string: $date, offset: 4, length: 2) % 4) {
            $daysInMonth[1] = 29;
        }

        return 0 < $day && $daysInMonth[$month - 1] >= $day;
    }

    #[Attribute\TwigFilter]
    public static function excelDate(int $timestamp, string $format = self::FORMAT): int|string
    {
        $base = 25569;

        if ($timestamp >= $base) {
            /** @noinspection SummerTimeUnsafeTimeManipulationInspection */
            $unix = ($timestamp - $base) * 86400;
            $date = gmdate(format: $format, timestamp: $unix);

            if (self::validateDateBasic(date: $date, format: $format)) {
                return $date;
            }
        }

        return $timestamp;
    }

    #[Attribute\TwigFunction]
    public static function validateDateBasic(mixed $date, string $format = self::FORMAT): bool
    {
        $object = DateTime::createFromFormat(format: $format, datetime: $date);

        return $object && $date === $object->format(format: $format);
    }

    #[Attribute\TwigFilter]
    #[Pure]
    public static function format(int|float $timestamp): string
    {
        $str = '';
        $timestamp = round(num: $timestamp * 1000);

        foreach (self::TIME as $unit => $value) {
            if ($timestamp >= $value) {
                $time = floor(num: $timestamp / $value);

                if ($time > 0) {
                    $str .= $time . ' ' . $unit . (1.0 === $time ? '' : 's') . ' ';
                }

                $timestamp -= ($time * $value);
            }
        }

        if ($timestamp > 0) {
            $str .= $timestamp . ' ms';
        }

        return trim(string: $str);
    }

    #[Attribute\TwigFilter]
    #[Pure]
    public static function formatToArray(int|float $timestamp): array
    {
        $timestamp = round(num: $timestamp * 1000);
        $result = [];

        foreach (self::TIME as $unit => $value) {
            if ($timestamp >= $value) {
                $time = floor(num: $timestamp / $value * 100.0 / 100.0);

                if ($time > 0) {
                    $result[$unit] = $time;
                }

                $timestamp -= ($time * $value);
            }
        }

        if ($timestamp > 0) {
            $result['micro'] = $timestamp;
        }

        return $result;
    }

    #[Attribute\TwigFilter]
    public static function formatDate(string $string, string $format = self::FORMAT): string
    {
        return DateTime::createFromFormat(format: $format, datetime: $string)?->format(format: self::FORMAT);
    }

    #[Attribute\TwigFilter]
    public static function getDateNullable(?string $format = null, ?string $dateString = null): ?DateTime
    {
        if (null === $dateString || !$date = DateTime::createFromFormat(format: $format, datetime: $dateString)) {
            return null;
        }

        return $date;
    }

    /**
     * @throws InvalidArgumentException
     */
    #[Attribute\TwigFilter]
    public static function getDate(?string $format = null, ?string $dateString = null): DateTime
    {
        if (null === $dateString || !$date = DateTime::createFromFormat(format: $format, datetime: $dateString)) {
            throw new InvalidArgumentException(message: 'Invalid date string');
        }

        return $date;
    }

    /**
     * @throws Exception
     */
    #[Attribute\TwigFilter]
    public static function createFromUnixTimestamp(int $timestamp = 0, ?string $format = null): string
    {
        return (new DateTime())->setTimestamp(timestamp: $timestamp)
            ->format(format: $format ?? self::FORMAT);
    }

    #[Attribute\TwigFilter]
    public static function guessDateFormat(string $date): DateTime|string
    {
        $formats = array_merge(Php::getClassConstantsValues(class: DateTime::class), self::EXTRA_FORMATS);

        foreach ($formats as $format) {
            $datetime = DateTime::createFromFormat(format: $format, datetime: $date);

            if ($datetime instanceof DateTime) {
                return $datetime;
            }
        }

        return $date;
    }
}
