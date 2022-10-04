<?php declare(strict_types = 1);

namespace Vairogs\Tests\Source\Utils\Helper;

use DateTimeInterface;
use Exception;
use InvalidArgumentException;
use Vairogs\Extra\Constants;
use Vairogs\Tests\Assets\VairogsTestCase;
use Vairogs\Utils\Helper\Date;

class DateTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\DateDataProvider::dataProviderValidateDate
     */
    public function testValidateDate(string $date, bool $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Date())->validateDate(date: $date));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\DateDataProvider::dataProviderGetDateWithoutFormat
     */
    public function testGetDateWithoutFormat(string $date): void
    {
        $this->assertInstanceOf(expected: DateTimeInterface::class, actual: (new Date())->getDateWithoutFormat(date: $date));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\DateDataProvider::dataProviderGetDateWithoutFormatWrong
     */
    public function testGetDateWithoutFormatWrong(string $date): void
    {
        $this->assertNotInstanceOf(expected: DateTimeInterface::class, actual: (new Date())->getDateWithoutFormat(date: $date));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\DateDataProvider::dataProviderExcelDate
     */
    public function testExcelDate(int $timestamp, string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Date())->excelDate($timestamp));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\DateDataProvider::dataProviderFormat
     */
    public function testFormat(int|float $timestamp, string|array $expected, bool $asArray): void
    {
        $this->assertEquals(expected: $expected, actual: (new Date())->format(timestamp: $timestamp, asArray: $asArray));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\DateDataProvider::dataProviderFormatDate
     */
    public function testFormatDate(string $date, string $format, string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Date())->formatDate(string: $date, format: $format));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\DateDataProvider::dataProviderFormatDateWrong
     */
    public function testFormatDateWrong(string $date, string $format): void
    {
        $this->assertFalse(condition: (new Date())->formatDate(string: $date, format: $format));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\DateDataProvider::dataProviderCreateFromUnixTimestamp
     *
     * @throws Exception
     */
    public function testCreateFromUnixTimestamp(int $timestamp, ?string $format, string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Date())->createFromUnixTimestamp(timestamp: $timestamp, format: $format));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\DateDataProvider::dataProviderGetDateNullable
     */
    public function testGetDateNullable(?string $date, ?string $format, ?string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Date())->getDateNullable(dateString: $date, format: $format)?->format(format: Constants\Date::FORMAT));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\DateDataProvider::dataProviderGetDate
     */
    public function testGetDate(string $date, string $format, string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Date())->getDate(dateString: $date, format: $format)->format(format: $format));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\DateDataProvider::dataProviderGetDateWrong
     */
    public function testGetDateWrong(?string $date, ?string $format): void
    {
        $this->expectException(exception: InvalidArgumentException::class);
        (new Date())->getDate(dateString: $date, format: $format);
    }
}
