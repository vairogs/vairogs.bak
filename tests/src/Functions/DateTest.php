<?php declare(strict_types = 1);

namespace Vairogs\Tests\Source\Functions;

use DateTimeInterface;
use Exception;
use InvalidArgumentException;
use Vairogs\Functions\Date;
use Vairogs\Tests\Assets\VairogsTestCase;

class DateTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Tests\Assets\Functions\DateDataProvider::dataProviderValidateDate
     */
    public function testValidateDate(string $date, bool $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Date())->validateDate(date: $date));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Functions\DateDataProvider::dataProviderGetDateWithoutFormat
     */
    public function testGetDateWithoutFormat(string $date): void
    {
        $this->assertInstanceOf(expected: DateTimeInterface::class, actual: (new Date())->getDateWithoutFormat(date: $date));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Functions\DateDataProvider::dataProviderGetDateWithoutFormatWrong
     */
    public function testGetDateWithoutFormatWrong(string $date): void
    {
        $this->assertNotInstanceOf(expected: DateTimeInterface::class, actual: (new Date())->getDateWithoutFormat(date: $date));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Functions\DateDataProvider::dataProviderExcelDate
     */
    public function testExcelDate(int $timestamp, string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Date())->excelDate($timestamp));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Functions\DateDataProvider::dataProviderFormatDate
     */
    public function testFormatDate(string $date, string $format, string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Date())->formatDate(string: $date, format: $format));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Functions\DateDataProvider::dataProviderFormatDateWrong
     */
    public function testFormatDateWrong(string $date, string $format): void
    {
        $this->assertFalse(condition: (new Date())->formatDate(string: $date, format: $format));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Functions\DateDataProvider::dataProviderCreateFromUnixTimestamp
     *
     * @throws Exception
     */
    public function testCreateFromUnixTimestamp(int $timestamp, ?string $format, string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Date())->createFromUnixTimestamp(timestamp: $timestamp, format: $format));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Functions\DateDataProvider::dataProviderGetDateNullable
     */
    public function testGetDateNullable(?string $date, ?string $format, ?string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Date())->getDateNullable(dateString: $date, format: $format)?->format(format: Date::FORMAT));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Functions\DateDataProvider::dataProviderGetDate
     */
    public function testGetDate(string $date, string $format, string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Date())->getDate(dateString: $date, format: $format)->format(format: $format));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Functions\DateDataProvider::dataProviderGetDateWrong
     */
    public function testGetDateWrong(?string $date, ?string $format): void
    {
        $this->expectException(exception: InvalidArgumentException::class);
        (new Date())->getDate(dateString: $date, format: $format);
    }
}
