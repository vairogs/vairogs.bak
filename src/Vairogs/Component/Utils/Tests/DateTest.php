<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils;

use PHPUnit\Framework\TestCase;
use Vairogs\Component\Utils\Date;

class DateTest extends TestCase
{
    private const PK_OLD_VALID = '111111-10258';
    private const PK_NEW_VALID = '320511-36626';
    private const PK_OLD_INVALID = '11111-11111';
    private const PK_NEW_INVALID = '323232-32323';
    private const PK_DATE_VALID = '120456';
    private const PK_DATE_INVALID = '320456';
    private const TIMESTAMP = 1546717009;
    private const TIMESTAMP_INVALID = 1546717005;
    private const DATE = '05-01-2019 19:36:49';
    private const DATE_INVALID = '05.01.2019 19:33:49';
    private const FORMAT_VALID = [
        '60' => '1 min',
        '3600' => '1 hour',
        '6' => '6 secs',
        '3666' => '1 hour 1 min 6 secs',
        '3606' => '1 hour 6 secs',
    ];
    private const FORMAT_INVALID = [
        '66' => '1 min',
        '3600' => '1 hours',
        '6' => '6 sec',
        '3666' => '1 hour 6 secs',
        '3606' => '1 hour 0 min 6 secs',
    ];

    public function testValidatePersonCode(): void
    {
        $this->assertTrue(Date::validatePersonCode(self::PK_OLD_VALID));
        $this->assertTrue(Date::validatePersonCode(self::PK_NEW_VALID));
        $this->assertFalse(Date::validatePersonCode(self::PK_OLD_INVALID));
        $this->assertFalse(Date::validatePersonCode(self::PK_NEW_INVALID));
    }

    public function testValidateDate(): void
    {
        $this->assertTrue(Date::validateDate(self::PK_DATE_VALID));
        $this->assertFalse(Date::validateDate(self::PK_DATE_INVALID));
    }

    public function testNewPKValidate(): void
    {
        $this->assertTrue(Date::validatePersonCode(self::PK_NEW_VALID));
        $this->assertFalse(Date::validatePersonCode(self::PK_NEW_INVALID));
    }

    public function testOldPKValidate(): void
    {
        $this->assertTrue(Date::validatePersonCode(self::PK_OLD_VALID));
        $this->assertFalse(Date::validatePersonCode(self::PK_OLD_INVALID));
    }

    public function testExcelDate(): void
    {
        $this->assertSame(self::DATE, Date::excelDate(self::TIMESTAMP, self::DATE));
        $this->assertNotSame(self::TIMESTAMP_INVALID, Date::excelDate(self::TIMESTAMP_INVALID, self::DATE));
    }

    public function testValidateDateBasic(): void
    {
        $this->assertTrue(Date::validateDateBasic(self::DATE));
        $this->assertFalse(Date::validateDateBasic(self::DATE_INVALID));
    }

    public function testFormat(): void
    {
        foreach (self::FORMAT_VALID as $time => $format) {
            $this->assertSame($format, Date::format($time));
        }
        foreach (self::FORMAT_INVALID as $time => $format) {
            $this->assertNotSame($format, Date::format($time));
        }
    }
}
