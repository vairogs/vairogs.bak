<?php declare(strict_types = 1);

namespace Vairogs\Functions;

use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use InvalidArgumentException;
use Vairogs\Functions\Constants\Month as M;

use function array_merge;
use function gmdate;
use function substr;

final class Date
{
    public const FORMAT = 'd-m-Y H:i:s';
    public const FORMAT_TS = 'D M d Y H:i:s T';

    public const EXTRA_FORMATS = [
        self::FORMAT,
        self::FORMAT_TS,
    ];

    public function validateDate(string $date): bool
    {
        $date = (new Text())->keepNumeric(text: $date);
        $day = (int) substr(string: $date, offset: 0, length: 2);
        $month = (int) substr(string: $date, offset: 2, length: 2);

        if (1 > $month || 12 < $month) {
            return false;
        }

        $daysInMonth = [M::JAN, M::FEB, M::MAR, M::APR, M::MAY, M::JUN, M::JUL, M::AUG, M::SEP, M::OCT, M::NOV, M::DEC];

        if (0 === (int) substr(string: $date, offset: 4, length: 2) % 4) {
            $daysInMonth[1] = M::FEB_LONG;
        }

        return 0 < $day && $daysInMonth[$month - 1] >= $day;
    }

    public function excelDate(int $timestamp, string $format = self::FORMAT): string
    {
        $base = 25569;

        if ($timestamp >= $base) {
            /** @noinspection SummerTimeUnsafeTimeManipulationInspection */
            $unix = ($timestamp - $base) * 86400;
            $date = gmdate(format: $format, timestamp: $unix);

            if ($this->validateDateBasic(date: $date, format: $format)) {
                return $date;
            }
        }

        return (string) $timestamp;
    }

    public function validateDateBasic(mixed $date, string $format = self::FORMAT): bool
    {
        $object = DateTimeImmutable::createFromFormat(format: '!' . $format, datetime: $date);

        return $object && $date === $object->format(format: $format);
    }

    public function formatDate(string $string, string $format = self::FORMAT): string|bool
    {
        if (($date = DateTimeImmutable::createFromFormat(format: '!' . $format, datetime: $string)) instanceof DateTimeImmutable) {
            return $date->format(format: self::FORMAT);
        }

        return false;
    }

    public function getDateNullable(?string $dateString = null, ?string $format = null): ?DateTimeInterface
    {
        if (null === $dateString || null === $format || !$date = DateTimeImmutable::createFromFormat(format: '!' . $format, datetime: $dateString)) {
            return null;
        }

        return $date;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getDate(?string $dateString = null, ?string $format = null): DateTimeInterface
    {
        if (null === $dateString || !$date = DateTimeImmutable::createFromFormat(format: '!' . $format, datetime: $dateString)) {
            throw new InvalidArgumentException(message: 'Invalid date string');
        }

        return $date;
    }

    /**
     * @throws Exception
     */
    public function createFromUnixTimestamp(int $timestamp = 0, ?string $format = null): string
    {
        return (new DateTimeImmutable())->setTimestamp(timestamp: $timestamp)->format(format: $format ?? self::FORMAT);
    }

    public function getDateWithoutFormat(string $date, array $guesses = []): DateTimeInterface|string
    {
        $formats = array_merge((new Php())->getClassConstantsValues(class: DateTimeImmutable::class), self::EXTRA_FORMATS, $guesses);

        foreach ($formats as $format) {
            $datetime = DateTimeImmutable::createFromFormat(format: '!' . $format, datetime: $date);

            if ($datetime instanceof DateTimeInterface) {
                return $datetime;
            }
        }

        return $date;
    }
}
