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
     * @dataProvider \Vairogs\Tests\Utils\Helper\DataProvider\DateDataProvider::dataProviderValidateDate
     */
    public function testValidateDate(string $date, bool $expected): void
    {
        $this->assertSame(expected: $expected, actual: Date::validateDate(date: $date));
    }

    /**
     * @dataProvider \Vairogs\Tests\Utils\Helper\DataProvider\DateDataProvider::dataProviderGetDateWithoutFormat
     */
    public function testGetDateWithoutFormat(string $date): void
    {
        $this->assertInstanceOf(expected: DateTime::class, actual: Date::getDateWithoutFormat(date: $date));
    }

    /**
     * @dataProvider \Vairogs\Tests\Utils\Helper\DataProvider\DateDataProvider::dataProviderGetDateWithoutFormatWrong
     */
    public function testGetDateWithoutFormatWrong(string $date): void
    {
        $this->assertNotInstanceOf(expected: DateTime::class, actual: Date::getDateWithoutFormat(date: $date));
    }

    /**
     * @dataProvider \Vairogs\Tests\Utils\Helper\DataProvider\DateDataProvider::dataProviderExcelDate
     */
    public function testExcelDate(int $timestamp, string $expected): void
    {
        $this->assertSame(expected: $expected, actual: Date::excelDate($timestamp));
    }

    /**
     * @dataProvider \Vairogs\Tests\Utils\Helper\DataProvider\DateDataProvider::dataProviderFormat
     */
    public function testFormat(int|float $timestamp, string $expected): void
    {
        $this->assertSame(expected: $expected, actual: Date::format(timestamp: $timestamp));
    }

    /**
     * @dataProvider \Vairogs\Tests\Utils\Helper\DataProvider\DateDataProvider::dataProviderFormatToArray
     */
    public function testFormatToArray(int|float $timestamp, array $expected): void
    {
        $this->assertSame(expected: $expected, actual: Date::formatToArray(timestamp: $timestamp));
    }

    /**
     * @dataProvider \Vairogs\Tests\Utils\Helper\DataProvider\DateDataProvider::dataProviderFormatDate
     */
    public function testFormatDate(string $date, string $format, string $expected): void
    {
        $this->assertSame(expected: $expected, actual: Date::formatDate(string: $date, format: $format));
    }

    /**
     * @dataProvider \Vairogs\Tests\Utils\Helper\DataProvider\DateDataProvider::dataProviderFormatDateWrong
     */
    public function testFormatDateWrong(string $date, string $format): void
    {
        $this->assertFalse(condition: Date::formatDate(string: $date, format: $format));
    }

    /**
     * @dataProvider \Vairogs\Tests\Utils\Helper\DataProvider\DateDataProvider::dataProviderCreateFromUnixTimestamp
     */
    public function testCreateFromUnixTimestamp(int $timestamp, ?string $format, string $expected): void
    {
        $this->assertSame(expected: $expected, actual: Date::createFromUnixTimestamp(timestamp: $timestamp, format: $format));
    }

    /**
     * @dataProvider \Vairogs\Tests\Utils\Helper\DataProvider\DateDataProvider::dataProviderGetDateNullable
     */
    public function testGetDateNullable(?string $date, ?string $format, ?string $expected): void
    {
        $this->assertSame(expected: $expected, actual: Date::getDateNullable(dateString: $date, format: $format)?->format(format: Constants\Date::FORMAT));
    }

    /**
     * @dataProvider \Vairogs\Tests\Utils\Helper\DataProvider\DateDataProvider::dataProviderGetDate
     */
    public function testGetDate(string $date, string $format, string $expected): void
    {
        $this->assertSame(expected: $expected, actual: Date::getDate(dateString: $date, format: $format)->format(format: $format));
    }

    /**
     * @dataProvider \Vairogs\Tests\Utils\Helper\DataProvider\DateDataProvider::dataProviderGetDateWrong
     */
    public function testGetDateWrong(?string $date, ?string $format): void
    {
        $this->expectException(exception: InvalidArgumentException::class);
        Date::getDate(dateString: $date, format: $format);
    }
}
