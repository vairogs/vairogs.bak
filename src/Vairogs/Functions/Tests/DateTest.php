<?php declare(strict_types = 1);

namespace Vairogs\Functions\Tests;

use DateTimeInterface;
use Exception;
use InvalidArgumentException;
use Vairogs\Core\Tests\VairogsTestCase;
use Vairogs\Functions\Date;

class DateTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\DateDataProvider::dataProviderValidateDate
     */
    public function testValidateDate(string $date, bool $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Date())->validateDate(date: $date));
    }

    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\DateDataProvider::dataProviderGetDateWithoutFormat
     */
    public function testGetDateWithoutFormat(string $date): void
    {
        $this->assertInstanceOf(expected: DateTimeInterface::class, actual: (new Date())->getDateWithoutFormat(date: $date));
    }

    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\DateDataProvider::dataProviderGetDateWithoutFormatWrong
     */
    public function testGetDateWithoutFormatWrong(string $date): void
    {
        $this->assertNotInstanceOf(expected: DateTimeInterface::class, actual: (new Date())->getDateWithoutFormat(date: $date));
    }

    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\DateDataProvider::dataProviderExcelDate
     */
    public function testExcelDate(int $timestamp, string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Date())->excelDate($timestamp));
    }

    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\DateDataProvider::dataProviderFormatDate
     */
    public function testFormatDate(string $date, string $format, string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Date())->formatDate(string: $date, format: $format));
    }

    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\DateDataProvider::dataProviderFormatDateWrong
     */
    public function testFormatDateWrong(string $date, string $format): void
    {
        $this->assertFalse(condition: (new Date())->formatDate(string: $date, format: $format));
    }

    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\DateDataProvider::dataProviderCreateFromUnixTimestamp
     *
     * @throws Exception
     */
    public function testCreateFromUnixTimestamp(int $timestamp, ?string $format, string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Date())->createFromUnixTimestamp(timestamp: $timestamp, format: $format));
    }

    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\DateDataProvider::dataProviderGetDateNullable
     */
    public function testGetDateNullable(?string $date, ?string $format, ?string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Date())->getDateNullable(dateString: $date, format: $format)?->format(format: Date::FORMAT));
    }

    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\DateDataProvider::dataProviderGetDate
     */
    public function testGetDate(string $date, string $format, string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Date())->getDate(dateString: $date, format: $format)->format(format: $format));
    }

    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\DateDataProvider::dataProviderGetDateWrong
     */
    public function testGetDateWrong(?string $date, ?string $format): void
    {
        $this->expectException(exception: InvalidArgumentException::class);
        (new Date())->getDate(dateString: $date, format: $format);
    }
}
