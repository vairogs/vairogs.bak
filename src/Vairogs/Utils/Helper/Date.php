<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use InvalidArgumentException;
use Vairogs\Extra\Constants;
use Vairogs\Extra\Constants\Month as M;
use Vairogs\Twig\Attribute\TwigFilter;
use Vairogs\Twig\Attribute\TwigFunction;

use function array_merge;
use function gmdate;
use function substr;

final class Date
{
    public const EXTRA_FORMATS = [
        Constants\Date::FORMAT,
        Constants\Date::FORMAT_TS,
    ];

    #[TwigFunction]
    #[TwigFilter]
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

    #[TwigFunction]
    #[TwigFilter]
    public function excelDate(int $timestamp, string $format = Constants\Date::FORMAT): string
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

    #[TwigFunction]
    #[TwigFilter]
    public function validateDateBasic(mixed $date, string $format = Constants\Date::FORMAT): bool
    {
        $object = DateTimeImmutable::createFromFormat(format: '!' . $format, datetime: $date);

        return $object && $date === $object->format(format: $format);
    }

    #[TwigFunction]
    #[TwigFilter]
    public function formatDate(string $string, string $format = Constants\Date::FORMAT): string|bool
    {
        if (($date = DateTimeImmutable::createFromFormat(format: '!' . $format, datetime: $string)) instanceof DateTimeImmutable) {
            return $date->format(format: Constants\Date::FORMAT);
        }

        return false;
    }

    #[TwigFunction]
    #[TwigFilter]
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
    #[TwigFunction]
    #[TwigFilter]
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
    #[TwigFunction]
    #[TwigFilter]
    public function createFromUnixTimestamp(int $timestamp = 0, ?string $format = null): string
    {
        return (new DateTimeImmutable())->setTimestamp(timestamp: $timestamp)->format(format: $format ?? Constants\Date::FORMAT);
    }

    #[TwigFunction]
    #[TwigFilter]
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
