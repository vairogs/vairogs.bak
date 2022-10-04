<?php declare(strict_types = 1);

namespace Vairogs\Tests\Assets\Utils\Helper;

use DateTimeImmutable;
use DateTimeInterface;
use Vairogs\Extra\Constants\Date;

class DateDataProvider
{
    public function dataProviderValidateDate(): array
    {
        return [
            ['08.04.2022', true, ],
            ['08.13.2022', false, ],
            ['00.00.0000', false, ],
        ];
    }

    public function dataProviderGetDateWithoutFormat(): array
    {
        return [
            [(new DateTimeImmutable())->format(format: DateTimeInterface::ATOM), ],
            [(new DateTimeImmutable())->format(format: DateTimeInterface::RFC1123), ],
            [(new DateTimeImmutable())->format(format: DateTimeInterface::RFC3339_EXTENDED), ],
            [(new DateTimeImmutable())->format(format: DateTimeInterface::W3C), ],
        ];
    }

    public function dataProviderGetDateWithoutFormatWrong(): array
    {
        return [
            ['02-02-98', ],
            ['08-04-2022', ],
            ['08-08-02', ],
            ['12-13-2022', ],
        ];
    }

    public function dataProviderExcelDate(): array
    {
        return [
            [25559, '25559', ],
            [25569, '01-01-1970 00:00:00', ],
            [44659, '08-04-2022 00:00:00', ],
        ];
    }

    public function dataProviderFormat(): array
    {
        return [
            [45, '45 seconds', false, ],
            [0.045, '45 micros', false, ],
            [3000, '50 minutes', false, ],
            [7200, '2 hours', false, ],
            [5400, '1 hour 30 minutes', false, ],
            [45, ['second' => 45, ], true, ],
            [0.045, ['micro' => 45, ], true, ],
            [3000, ['minute' => 50, ], true, ],
            [7200, ['hour' => 2, ], true, ],
            [5400, ['hour' => 1, 'minute' => 30, ], true, ],
        ];
    }

    public function dataProviderFormatDate(): array
    {
        $dateTime = new DateTimeImmutable();

        return [
            [$dateTime->format(format: DateTimeInterface::ATOM),  DateTimeInterface::ATOM, $dateTime->format(format: Date::FORMAT)],
            [$dateTime->format(format: DateTimeInterface::RFC3339_EXTENDED), DateTimeInterface::RFC3339_EXTENDED, $dateTime->format(format: Date::FORMAT)],
        ];
    }

    public function dataProviderFormatDateWrong(): array
    {
        $dateTime = new DateTimeImmutable();

        return [
            [$dateTime->format(format: DateTimeInterface::ATOM), DateTimeInterface::RFC1036],
            [$dateTime->format(format: DateTimeInterface::RFC3339_EXTENDED), DateTimeInterface::ATOM],
        ];
    }

    public function dataProviderCreateFromUnixTimestamp(): array
    {
        return [
            [1649403032, DateTimeInterface::ATOM, (new DateTimeImmutable())->setTimestamp(timestamp: 1649403032)->format(format: DateTimeInterface::ATOM)],
            [1649403032, null, (new DateTimeImmutable())->setTimestamp(timestamp: 1649403032)->format(format: Date::FORMAT)],
        ];
    }

    public function dataProviderGetDateNullable(): array
    {
        $dateTime = new DateTimeImmutable();

        return [
            [$dateTime->format(format: DateTimeInterface::ATOM), DateTimeInterface::ATOM, $dateTime->format(format: Date::FORMAT)],
            [null, null, null],
        ];
    }

    public function dataProviderGetDate(): array
    {
        $dateTime = new DateTimeImmutable();

        return [
            [$dateTime->format(format: DateTimeInterface::ATOM), DateTimeInterface::ATOM, $dateTime->format(format: DateTimeInterface::ATOM)],
        ];
    }

    public function dataProviderGetDateWrong(): array
    {
        return [
            [null, null],
            ['test', DateTimeInterface::ATOM],
        ];
    }
}
