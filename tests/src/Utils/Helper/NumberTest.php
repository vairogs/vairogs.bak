<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils\Helper;

use PHPUnit\Framework\TestCase;
use Vairogs\Utils\Helper\Number;

class NumberTest extends TestCase
{
    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\NumberDataProvider::dataProviderIsIntFloat
     */
    public function testIsInt(int|float $number, bool $expected): void
    {
        $this->assertEquals(expected: $expected, actual: Number::isInt(value: $number));
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\NumberDataProvider::dataProviderIsIntFloat
     */
    public function testIsFloat(int|float $number, bool $expected): void
    {
        $this->assertEquals(expected: !$expected, actual: Number::isFloat(value: $number));
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\NumberDataProvider::dataProviderGreatestCommonDivisor
     */
    public function testGreatestCommonDivisor(int $x, int $y, int $expected): void
    {
        $this->assertEquals(expected: $expected, actual: Number::greatestCommonDivisor(x: $x, y: $y));
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\NumberDataProvider::dataProviderLeastCommonMultiple
     */
    public function testLeastCommonMultiple(int $x, int $y, int $expected): void
    {
        $this->assertEquals(expected: $expected, actual: Number::leastCommonMultiple(x: $x, y: $y));
    }
}
