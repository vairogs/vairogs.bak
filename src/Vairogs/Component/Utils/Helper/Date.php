<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Helper;

use DateTime;
use Exception;
use InvalidArgumentException;
use ReflectionException;
use function array_merge;
use function floor;
use function gmdate;
use function round;
use function str_replace;
use function strlen;
use function substr;
use const false;
use const true;

class Date
{
    public const FORMAT = 'd-m-Y H:i:s';
    public const EXTRA_FORMATS = [
        'D M d Y H:i:s T',
    ];

    public const SEC = 1000;
    public const MIN = 60 * self::SEC;
    public const HOUR = 60 * self::MIN;

    public const TIME = [
        'hour' => self::HOUR,
        'min' => self::MIN,
        'sec' => self::SEC,
    ];

    /**
     * @param $personCode
     *
     * @return bool
     */
    public static function validatePersonCode(string $personCode): bool
    {
        $personCode = Text::keepNumeric($personCode);
        if (11 !== strlen($personCode)) {
            return false;
        }

        if (32 === (int)substr($personCode, 0, 2)) {
            if (!self::validateNewPersonCode($personCode)) {
                return false;
            }
        } else {
            if (!self::validateDate($personCode)) {
                return false;
            }
            if (!self::validateOldPersonCode($personCode)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param $personCode
     *
     * @return bool
     */
    public static function validateNewPersonCode(string $personCode): bool
    {
        $personCode = str_replace('-', '', $personCode);
        // @formatter:off
        $calculations = [1, 6, 3, 7, 9, 10, 5, 8, 4, 2,];
        // @formatter:on
        $sum = 0;
        foreach ($calculations as $key => $calculation) {
            $sum += ($personCode[$key] * $calculation);
        }
        $remainder = $sum % 11;
        if (-1 > 1 - $remainder) {
            return (1 - $remainder + 11) === (int)$personCode[10];
        }

        return (1 - $remainder) === (int)$personCode[10];
    }

    /**
     * @param $date
     *
     * @return bool
     */
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

    /**
     * @param $personCode
     *
     * @return bool
     */
    public static function validateOldPersonCode(string $personCode): bool
    {
        $personCode = str_replace('-', '', $personCode);
        $check = '01060307091005080402';
        $checksum = 1;
        for ($i = 0; $i < 10; $i++) {
            $checksum -= (int)$personCode[$i] * (int)substr($check, $i * 2, 2);
        }

        return !((int)($checksum - floor($checksum / 11) * 11) !== (int)$personCode[10]);
    }

    /**
     * @param int $timestamp
     * @param string $format
     *
     * @return string|int
     */
    public static function excelDate(int $timestamp, $format = self::FORMAT)
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

    /**
     * @param $date
     * @param string $format
     *
     * @return bool
     */
    public static function validateDateBasic($date, $format = self::FORMAT): bool
    {
        $object = DateTime::createFromFormat($format, $date);

        return $object && $date === $object->format($format);
    }

    /**
     * @param $timestamp
     *
     * @return string
     */
    public static function format($timestamp): string
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

    /**
     * @param string $string
     * @param string $format
     *
     * @return string|null
     */
    public static function formatDate(string $string, string $format = self::FORMAT): ?string
    {
        $datetime = DateTime::createFromFormat($format, $string);

        return $datetime ? $datetime->format(self::FORMAT) : null;
    }

    /**
     * @param string|null $format
     * @param string|null $dateString
     *
     * @return DateTime|null
     */
    public static function getDateNullable(?string $format = null, ?string $dateString = null): ?DateTime
    {
        if (!$date = DateTime::createFromFormat($format, $dateString)) {
            return null;
        }

        return $date;
    }

    /**
     * @param string|null $format
     * @param string|null $dateString
     *
     * @return DateTime
     * @throws InvalidArgumentException
     */
    public static function getDate(?string $format = null, ?string $dateString = null): DateTime
    {
        if (!$date = DateTime::createFromFormat($format, $dateString)) {
            throw new InvalidArgumentException('Invalid date string');
        }

        return $date;
    }

    /**
     * @param int $timestamp
     * @param string|null $format
     *
     * @return string
     * @throws Exception
     */
    public static function createFromUnixTimestamp(int $timestamp = 0, string $format = null): string
    {
        return (new DateTime())->setTimestamp($timestamp)->format($format ?? self::FORMAT);
    }

    /**
     * @param string $date
     *
     * @return DateTime|string
     * @throws ReflectionException
     */
    public static function guessDateFormat(string $date)
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
