<?php declare(strict_types = 1);

namespace Vairogs\Tests\Assets\Utils\Helper;

use DateTime;
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
            [(new DateTime())->format(format: DateTimeInterface::ATOM), ],
            [(new DateTime())->format(format: DateTimeInterface::RFC1123), ],
            [(new DateTime())->format(format: DateTimeInterface::RFC3339_EXTENDED), ],
            [(new DateTime())->format(format: DateTimeInterface::W3C), ],
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
            [45, '45 seconds', ],
            [0.045, '45 ms', ],
            [3000, '50 minutes', ],
            [7200, '2 hours', ],
            [5400, '1 hour 30 minutes', ],
        ];
    }

    public function dataProviderFormatToArray(): array
    {
        return [
            [45, ['second' => 45, ], ],
            [0.045, ['micro' => 45, ], ],
            [3000, ['minute' => 50, ], ],
            [7200, ['hour' => 2, ], ],
            [5400, ['hour' => 1, 'minute' => 30, ], ],
        ];
    }

    public function dataProviderFormatDate(): array
    {
        $dateTime = new DateTime();

        return [
            [$dateTime->format(format: DateTimeInterface::ATOM),  DateTimeInterface::ATOM, $dateTime->format(format: Date::FORMAT)],
            [$dateTime->format(format: DateTimeInterface::RFC3339_EXTENDED), DateTimeInterface::RFC3339_EXTENDED, $dateTime->format(format: Date::FORMAT)],
        ];
    }

    public function dataProviderFormatDateWrong(): array
    {
        $dateTime = new DateTime();

        return [
            [$dateTime->format(format: DateTimeInterface::ATOM), DateTimeInterface::RFC1036],
            [$dateTime->format(format: DateTimeInterface::RFC3339_EXTENDED), DateTimeInterface::ATOM],
        ];
    }

    public function dataProviderCreateFromUnixTimestamp(): array
    {
        return [
            [1649403032, DateTimeInterface::ATOM, (new DateTime())->setTimestamp(timestamp: 1649403032)->format(format: DateTimeInterface::ATOM)],
            [1649403032, null, (new DateTime())->setTimestamp(timestamp: 1649403032)->format(format: Date::FORMAT)],
        ];
    }

    public function dataProviderGetDateNullable(): array
    {
        $dateTime = new DateTime();

        return [
            [$dateTime->format(format: DateTimeInterface::ATOM), DateTimeInterface::ATOM, $dateTime->format(format: Date::FORMAT)],
            [null, null, null],
        ];
    }

    public function dataProviderGetDate(): array
    {
        $dateTime = new DateTime();

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
