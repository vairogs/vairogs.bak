<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use DateTime;
use Exception;
use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use Vairogs\Extra\Constants;
use Vairogs\Utils\Twig\Attribute;
use function array_merge;
use function floor;
use function gmdate;
use function round;
use function substr;
use function trim;

final class Date
{
    final public const EXTRA_FORMATS = [
        Constants\Date::FORMAT,
        Constants\Date::FORMAT_TS,
    ];

    final public const TIME = [
        'hour' => Constants\Date::HOUR,
        'minute' => Constants\Date::MIN,
        'second' => Constants\Date::SEC,
    ];

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function validateDate(string $date): bool
    {
        $date = Text::keepNumeric(text: $date);
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

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function excelDate(int $timestamp, string $format = Constants\Date::FORMAT): string
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

        return (string) $timestamp;
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function validateDateBasic(mixed $date, string $format = Constants\Date::FORMAT): bool
    {
        $object = DateTime::createFromFormat(format: $format, datetime: $date);

        return $object && $date === $object->format(format: $format);
    }

    #[Attribute\TwigFunction]
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

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    #[Pure]
    public static function formatToArray(int|float $timestamp): array
    {
        $timestamp = round(num: $timestamp * 1000);
        $result = [];

        foreach (self::TIME as $unit => $value) {
            if ($timestamp >= $value) {
                $time = (int) floor(num: $timestamp / $value);

                if ($time > 0) {
                    $result[$unit] = $time;
                }

                $timestamp -= ($time * $value);
            }
        }

        if ($timestamp > 0) {
            $result['micro'] = (int) $timestamp;
        }

        return $result;
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function formatDate(string $string, string $format = Constants\Date::FORMAT): string|bool
    {
        if (($date = DateTime::createFromFormat(format: $format, datetime: $string)) instanceof DateTime) {
            return $date->format(format: Constants\Date::FORMAT);
        }

        return false;
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function getDateNullable(?string $dateString = null, ?string $format = null): ?DateTime
    {
        if (null === $dateString || null === $format || !$date = DateTime::createFromFormat(format: $format, datetime: $dateString)) {
            return null;
        }

        return $date;
    }

    /**
     * @throws InvalidArgumentException
     */
    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function getDate(?string $dateString = null, ?string $format = null): DateTime
    {
        if (null === $dateString || !$date = DateTime::createFromFormat(format: $format, datetime: $dateString)) {
            throw new InvalidArgumentException(message: 'Invalid date string');
        }

        return $date;
    }

    /**
     * @throws Exception
     */
    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function createFromUnixTimestamp(int $timestamp = 0, ?string $format = null): string
    {
        return (new DateTime())->setTimestamp(timestamp: $timestamp)->format(format: $format ?? Constants\Date::FORMAT);
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function getDateWithoutFormat(string $date, array $guesses = []): DateTime|string
    {
        $formats = array_merge(Php::getClassConstantsValues(class: DateTime::class), self::EXTRA_FORMATS, $guesses);

        foreach ($formats as $format) {
            $datetime = DateTime::createFromFormat(format: $format, datetime: $date);

            if ($datetime instanceof DateTime) {
                return $datetime;
            }
        }

        return $date;
    }
}
