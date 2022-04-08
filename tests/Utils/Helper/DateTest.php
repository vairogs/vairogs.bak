<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils\Helper;

use DateTime;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Vairogs\Extra\Constants;
use Vairogs\Utils\Helper\Date;

class DateTest extends TestCase
{
    /**
     * @dataProvider dataProviderValidateDate
     */
    public function testValidateDate(string $date, bool $expected): void
    {
        $this->assertSame(expected: $expected, actual: Date::validateDate(date: $date));
    }

    public function dataProviderValidateDate(): array
    {
        return [
            ['08.04.2022',  true],
            ['08.13.2022', false],
            ['00.00.0000', false],
        ];
    }

    /**
     * @dataProvider dataProviderGetDateWithoutFormat
     */
    public function testGetDateWithoutFormat(string $date): void
    {
        $this->assertInstanceOf(expected: DateTime::class, actual: Date::getDateWithoutFormat(date: $date));
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

    /**
     * @dataProvider dataProviderGetDateWithoutFormatWrong
     */
    public function testGetDateWithoutFormatWrong(string $date): void
    {
        $this->assertNotInstanceOf(expected: DateTime::class, actual: Date::getDateWithoutFormat(date: $date));
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

    /**
     * @dataProvider dataProviderExcelDate
     */
    public function testExcelDate(int $timestamp, string $expected): void
    {
        $this->assertSame(expected: $expected, actual: Date::excelDate($timestamp));
    }

    public function dataProviderExcelDate(): array
    {
        return [
            [25559,               '25559'],
            [25569, '01-01-1970 00:00:00'],
            [44659, '08-04-2022 00:00:00'],
        ];
    }

    /**
     * @dataProvider dataProviderFormat
     */
    public function testFormat(int|float $timestamp, string $expected): void
    {
        $this->assertSame(expected: $expected, actual: Date::format(timestamp: $timestamp));
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

    /**
     * @dataProvider dataProviderFormatToArray
     */
    public function testFormatToArray(int|float $timestamp, array $expected): void
    {
        $this->assertSame(expected: $expected, actual: Date::formatToArray(timestamp: $timestamp));
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

    /**
     * @dataProvider dataProviderFormatDate
     */
    public function testFormatDate(string $date, string $format, string $expected): void
    {
        $this->assertSame(expected: $expected, actual: Date::formatDate(string: $date, format: $format));
    }

    public function dataProviderFormatDate(): array
    {
        $date = new DateTime();

        return [
            [$date->format(format: DateTime::ATOM),             DateTime::ATOM,             $date->format(format: Constants\Date::FORMAT)],
            [$date->format(format: DateTime::RFC3339_EXTENDED), DateTime::RFC3339_EXTENDED, $date->format(format: Constants\Date::FORMAT)],
        ];
    }

    /**
     * @dataProvider dataProviderFormatDateWrong
     */
    public function testFormatDateWrong(string $date, string $format): void
    {
        $this->assertFalse(condition: Date::formatDate(string: $date, format: $format));
    }

    public function dataProviderFormatDateWrong(): array
    {
        $date = new DateTime();

        return [
            [$date->format(format: DateTime::ATOM),          DateTime::RFC1036],
            [$date->format(format: DateTime::RFC3339_EXTENDED), DateTime::ATOM],
        ];
    }

    /**
     * @dataProvider dataProviderCreateFromUnixTimestamp
     */
    public function testCreateFromUnixTimestamp(int $timestamp, ?string $format, string $expected): void
    {
        $this->assertSame(expected: $expected, actual: Date::createFromUnixTimestamp(timestamp: $timestamp, format: $format));
    }

    public function dataProviderCreateFromUnixTimestamp(): array
    {
        return [
            [1649403032, DateTime::ATOM, (new DateTime())->setTimestamp(timestamp: 1649403032)->format(format: DateTime::ATOM)],
            [1649403032, null,   (new DateTime())->setTimestamp(timestamp: 1649403032)->format(format: Constants\Date::FORMAT)],
        ];
    }

    /**
     * @dataProvider dataProviderGetDateNullable
     */
    public function testGetDateNullable(?string $date, ?string $format, ?string $expected): void
    {
        $this->assertSame(expected: $expected, actual: Date::getDateNullable(dateString: $date, format: $format)?->format(format: Constants\Date::FORMAT));
    }

    public function dataProviderGetDateNullable(): array
    {
        $date = new DateTime();

        return [
            [$date->format(format: DateTime::ATOM), DateTime::ATOM, $date->format(format: Constants\Date::FORMAT)],
            [null, null, null],
        ];
    }

    /**
     * @dataProvider dataProviderGetDate
     */
    public function testGetDate(string $date, string $format, string $expected): void
    {
        $this->assertSame(expected: $expected, actual: Date::getDate(dateString: $date, format: $format)->format(format: $format));
    }

    public function dataProviderGetDate(): array
    {
        $date = new DateTime();

        return [
            [$date->format(format: DateTime::ATOM), DateTime::ATOM, $date->format(format: DateTime::ATOM)],
        ];
    }

    /**
     * @dataProvider dataProviderGetDateWrong
     */
    public function testGetDateWrong(?string $date, ?string $format): void
    {
        $this->expectException(exception: InvalidArgumentException::class);
        Date::getDate(dateString: $date, format: $format);
    }

    public function dataProviderGetDateWrong(): array
    {
        return [
            [null,             null],
            ['test', DateTime::ATOM],
        ];
    }
}
