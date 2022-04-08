<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils\Helper\DataProvider;

use DateTime;
use Vairogs\Extra\Constants;

class DateDataProvider
{
    public function dataProviderValidateDate(): array
    {
        return [
            ['08.04.2022',  true],
            ['08.13.2022', false],
            ['00.00.0000', false],
        ];
    }

    public function dataProviderGetDateWithoutFormat(): array
    {
        return [
            [(new DateTime())->format(format: DateTime::ATOM)],
            [(new DateTime())->format(format: DateTime::RFC1123)],
            [(new DateTime())->format(format: DateTime::RFC3339_EXTENDED)],
            [(new DateTime())->format(format: DateTime::ISO8601)],
        ];
    }

    public function dataProviderGetDateWithoutFormatWrong(): array
    {
        return [
            ['02-02-98'],
            ['08-04-2022'],
            ['08-08-02'],
            ['12-13-2022'],
        ];
    }

    public function dataProviderExcelDate(): array
    {
        return [
            [25559,               '25559'],
            [25569, '01-01-1970 00:00:00'],
            [44659, '08-04-2022 00:00:00'],
        ];
    }

    public function dataProviderFormat(): array
    {
        return [
            [45,          '45 seconds'],
            [0.045,            '45 ms'],
            [3000,        '50 minutes'],
            [7200,           '2 hours'],
            [5400, '1 hour 30 minutes'],
        ];
    }

    public function dataProviderFormatToArray(): array
    {
        return [
            [45,                  ['second' => 45]],
            [0.045,                ['micro' => 45]],
            [3000,                ['minute' => 50]],
            [7200,                   ['hour' => 2]],
            [5400,   ['hour' => 1, 'minute' => 30]],
        ];
    }

    public function dataProviderFormatDate(): array
    {
        $date = new DateTime();

        return [
            [$date->format(format: DateTime::ATOM),             DateTime::ATOM,             $date->format(format: Constants\Date::FORMAT)],
            [$date->format(format: DateTime::RFC3339_EXTENDED), DateTime::RFC3339_EXTENDED, $date->format(format: Constants\Date::FORMAT)],
        ];
    }

    public function dataProviderFormatDateWrong(): array
    {
        $date = new DateTime();

        return [
            [$date->format(format: DateTime::ATOM),          DateTime::RFC1036],
            [$date->format(format: DateTime::RFC3339_EXTENDED), DateTime::ATOM],
        ];
    }

    public function dataProviderCreateFromUnixTimestamp(): array
    {
        return [
            [1649403032, DateTime::ATOM, (new DateTime())->setTimestamp(timestamp: 1649403032)->format(format: DateTime::ATOM)],
            [1649403032, null,   (new DateTime())->setTimestamp(timestamp: 1649403032)->format(format: Constants\Date::FORMAT)],
        ];
    }

    public function dataProviderGetDateNullable(): array
    {
        $date = new DateTime();

        return [
            [$date->format(format: DateTime::ATOM), DateTime::ATOM, $date->format(format: Constants\Date::FORMAT)],
            [null, null, null],
        ];
    }

    public function dataProviderGetDate(): array
    {
        $date = new DateTime();

        return [
            [$date->format(format: DateTime::ATOM), DateTime::ATOM, $date->format(format: DateTime::ATOM)],
        ];
    }

    public function dataProviderGetDateWrong(): array
    {
        return [
            [null,             null],
            ['test', DateTime::ATOM],
        ];
    }
}
