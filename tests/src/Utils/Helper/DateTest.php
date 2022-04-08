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
     * @dataProvider \Vairogs\Assets\Utils\Helper\DateDataProvider::dataProviderValidateDate
     */
    public function testValidateDate(string $date, bool $expected): void
    {
        $this->assertSame(expected: $expected, actual: Date::validateDate(date: $date));
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\DateDataProvider::dataProviderGetDateWithoutFormat
     */
    public function testGetDateWithoutFormat(string $date): void
    {
        $this->assertInstanceOf(expected: DateTime::class, actual: Date::getDateWithoutFormat(date: $date));
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\DateDataProvider::dataProviderGetDateWithoutFormatWrong
     */
    public function testGetDateWithoutFormatWrong(string $date): void
    {
        $this->assertNotInstanceOf(expected: DateTime::class, actual: Date::getDateWithoutFormat(date: $date));
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\DateDataProvider::dataProviderExcelDate
     */
    public function testExcelDate(int $timestamp, string $expected): void
    {
        $this->assertSame(expected: $expected, actual: Date::excelDate($timestamp));
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\DateDataProvider::dataProviderFormat
     */
    public function testFormat(int|float $timestamp, string $expected): void
    {
        $this->assertSame(expected: $expected, actual: Date::format(timestamp: $timestamp));
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\DateDataProvider::dataProviderFormatToArray
     */
    public function testFormatToArray(int|float $timestamp, array $expected): void
    {
        $this->assertSame(expected: $expected, actual: Date::formatToArray(timestamp: $timestamp));
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\DateDataProvider::dataProviderFormatDate
     */
    public function testFormatDate(string $date, string $format, string $expected): void
    {
        $this->assertSame(expected: $expected, actual: Date::formatDate(string: $date, format: $format));
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\DateDataProvider::dataProviderFormatDateWrong
     */
    public function testFormatDateWrong(string $date, string $format): void
    {
        $this->assertFalse(condition: Date::formatDate(string: $date, format: $format));
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\DateDataProvider::dataProviderCreateFromUnixTimestamp
     */
    public function testCreateFromUnixTimestamp(int $timestamp, ?string $format, string $expected): void
    {
        $this->assertSame(expected: $expected, actual: Date::createFromUnixTimestamp(timestamp: $timestamp, format: $format));
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\DateDataProvider::dataProviderGetDateNullable
     */
    public function testGetDateNullable(?string $date, ?string $format, ?string $expected): void
    {
        $this->assertSame(expected: $expected, actual: Date::getDateNullable(dateString: $date, format: $format)?->format(format: Constants\Date::FORMAT));
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\DateDataProvider::dataProviderGetDate
     */
    public function testGetDate(string $date, string $format, string $expected): void
    {
        $this->assertSame(expected: $expected, actual: Date::getDate(dateString: $date, format: $format)->format(format: $format));
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\DateDataProvider::dataProviderGetDateWrong
     */
    public function testGetDateWrong(?string $date, ?string $format): void
    {
        $this->expectException(exception: InvalidArgumentException::class);
        Date::getDate(dateString: $date, format: $format);
    }
}
