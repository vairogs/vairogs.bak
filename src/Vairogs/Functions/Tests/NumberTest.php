<?php declare(strict_types = 1);

namespace Vairogs\Functions\Tests;

use Vairogs\Core\Tests\VairogsTestCase;
use Vairogs\Functions\Number;
use Vairogs\Utils\Encryption\Cross;
use Vairogs\Utils\Encryption\XXTEA;

class NumberTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\NumberDataProvider::dataProviderIsIntFloat
     */
    public function testIsInt(int|float $number, bool $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Number())->isInt(value: $number));
    }

    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\NumberDataProvider::dataProviderIsIntFloat
     */
    public function testIsFloat(int|float $number, bool $expected): void
    {
        $this->assertEquals(expected: !$expected, actual: (new Number())->isFloat(value: $number));
    }

    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\NumberDataProvider::dataProviderGreatestCommonDivisor
     */
    public function testGreatestCommonDivisor(int $x, int $y, int $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Number())->greatestCommonDivisor(fisrt: $x, second: $y));
    }

    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\NumberDataProvider::dataProviderLeastCommonMultiple
     */
    public function testLeastCommonMultiple(int $x, int $y, int $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Number())->leastCommonMultiple(first: $x, second: $y));
    }

    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\NumberDataProvider::dataProviderEncryption
     */
    public function testDecryptXXTEA(string $string, string $key): void
    {
        $this->assertEquals(expected: $string, actual: (new XXTEA())->decrypt(string: (new XXTEA())->encrypt(string: $string, key: $key), key: $key));
    }

    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\NumberDataProvider::dataProviderEncryption
     */
    public function testDecryptCross(string $string, string $key): void
    {
        $this->assertEquals(expected: $string, actual: (new Cross())->decrypt(string: (new Cross())->encrypt(string: $string, key: $key), key: $key));
    }
}
