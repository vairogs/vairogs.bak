<?php declare(strict_types = 1);

namespace Vairogs\Tests\Source\Utils\Helper;

use Vairogs\Tests\Assets\VairogsTestCase;
use Vairogs\Utils\Helper\Number;

class NumberTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\NumberDataProvider::dataProviderIsIntFloat
     */
    public function testIsInt(int|float $number, bool $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Number())->isInt(value: $number));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\NumberDataProvider::dataProviderIsIntFloat
     */
    public function testIsFloat(int|float $number, bool $expected): void
    {
        $this->assertEquals(expected: !$expected, actual: (new Number())->isFloat(value: $number));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\NumberDataProvider::dataProviderGreatestCommonDivisor
     */
    public function testGreatestCommonDivisor(int $x, int $y, int $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Number())->greatestCommonDivisor(fisrt: $x, second: $y));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\NumberDataProvider::dataProviderLeastCommonMultiple
     */
    public function testLeastCommonMultiple(int $x, int $y, int $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Number())->leastCommonMultiple(first: $x, second: $y));
    }
}
