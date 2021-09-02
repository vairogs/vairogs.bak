<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Helper;

use DateTime;
use Exception;
use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use ReflectionException;
use Vairogs\Component\Utils\Twig\Annotation;
use function array_merge;
use function floor;
use function gmdate;
use function round;
use function substr;
use function trim;

class Date
{
    public const FORMAT = 'd-m-Y H:i:s';
    public const FORMAT_TS = 'D M d Y H:i:s T';
    public const EXTRA_FORMATS = [
        self::FORMAT,
        self::FORMAT_TS,
    ];
    public const SEC = 1000;
    public const MIN = 60 * self::SEC;
    public const HOUR = 60 * self::MIN;
    public const TIME = [
        'hour' => self::HOUR,
        'minute' => self::MIN,
        'second' => self::SEC,
    ];

    #[Annotation\TwigFunction]
    public static function validateDate(string $date): bool
    {
        $date = Text::keepNumeric($date);
        $day = (int)substr($date, 0, 2);
        $month = (int)substr($date, 2, 2);

        if ($month < 0 || $month > 12) {
            return false;
        }

        // @formatter:off
        $months = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31,];
        // @formatter:on

        if (0 === (int)substr($date, 4, 2) % 4) {
            $months[1] = 29;
        }

        return 0 < $day && $months[$month - 1] >= $day;
    }

    #[Annotation\TwigFilter]
    public static function excelDate(int $timestamp, string $format = self::FORMAT): int|string
    {
        $base = 25569;

        if ($timestamp >= $base) {
            /** @noinspection SummerTimeUnsafeTimeManipulationInspection */
            $unix = ($timestamp - $base) * 86400;
            $date = gmdate($format, $unix);

            if (self::validateDateBasic($date, $format)) {
                return $date;
            }
        }

        return $timestamp;
    }

    #[Annotation\TwigFunction]
    public static function validateDateBasic(mixed $date, string $format = self::FORMAT): bool
    {
        $object = DateTime::createFromFormat($format, $date);

        return $object && $date === $object->format($format);
    }

    #[Annotation\TwigFilter]
    #[Pure]
    public static function format(int|float $timestamp): string
    {
        $str = '';
        $timestamp = round($timestamp * 1000);

        foreach (self::TIME as $unit => $value) {
            if ($timestamp >= $value) {
                $time = floor($timestamp / $value * 100.0 / 100.0);

                if ($time > 0) {
                    $str .= $time . ' ' . $unit . (1.0 === $time ? '' : 's') . ' ';
                }

                $timestamp -= ($time * $value);
            }
        }

        if ($timestamp > 0) {
            $str .= $timestamp . ' ms';
        }

        return trim($str);
    }

    #[Annotation\TwigFilter]
    #[Pure]
    public static function formatToArray(int|float $timestamp): array
    {
        $timestamp = round($timestamp * 1000);
        $result = [];

        foreach (self::TIME as $unit => $value) {
            if ($timestamp >= $value) {
                $time = floor($timestamp / $value * 100.0 / 100.0);

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

    #[Annotation\TwigFilter]
    public static function formatDate(string $string, string $format = self::FORMAT): string
    {
        return DateTime::createFromFormat($format, $string)?->format(self::FORMAT);
    }

    #[Annotation\TwigFilter]
    public static function getDateNullable(?string $format = null, ?string $dateString = null): ?DateTime
    {
        if (!$date = DateTime::createFromFormat($format, $dateString)) {
            return null;
        }

        return $date;
    }

    /**
     * @throws InvalidArgumentException
     */
    #[Annotation\TwigFilter]
    public static function getDate(?string $format = null, ?string $dateString = null): DateTime
    {
        if (!$date = DateTime::createFromFormat($format, $dateString)) {
            throw new InvalidArgumentException('Invalid date string');
        }

        return $date;
    }

    /**
     * @throws Exception
     */
    #[Annotation\TwigFilter]
    public static function createFromUnixTimestamp(int $timestamp = 0, string $format = null): string
    {
        return (new DateTime())->setTimestamp($timestamp)
            ->format($format ?? self::FORMAT);
    }

    /**
     * @throws ReflectionException
     */
    #[Annotation\TwigFilter]
    public static function guessDateFormat(string $date): DateTime|string
    {
        $formats = array_merge(Php::getClassConstantsValues(DateTime::class), self::EXTRA_FORMATS);

        foreach ($formats as $format) {
            $datetime = DateTime::createFromFormat($format, $date);

            if ($datetime instanceof DateTime) {
                return $datetime;
            }
        }

        return $date;
    }
}
