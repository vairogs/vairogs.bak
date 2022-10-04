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
