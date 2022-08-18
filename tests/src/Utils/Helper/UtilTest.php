<?php declare(strict_types = 1);

namespace Vairogs\Tests\Source\Utils\Helper;

use Vairogs\Tests\Assets\VairogsTestCase;
use Vairogs\Utils\Helper\Util;

class UtilTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\UtilDataProvider::dataProviderIsPrime
     */
    public function testIsPrime(int $number, bool $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Util())->isPrime(number: $number));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\UtilDataProvider::dataProviderIsPrime
     */
    public function testIsPrimeNoGMP(int $number, bool $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Util())->isPrime(number: $number, override: true));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\UtilDataProvider::dataProviderIsPrime
     */
    public function testIsPrimeBelow1000(int $number, bool $expected, ?bool $expectedBelow): void
    {
        $this->assertEquals(expected: $expectedBelow, actual: (new Util())->isPrimeBelow1000(number: $number));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\UtilDataProvider::dataProviderMakeOneDimension
     */
    public function testMakeOneDimension(array $input, bool $onlyLast, int $depth, int $maxDepth, array $expected): void
    {
        $this->assertEquals($expected, (new Util())->makeOneDimension(array: $input, onlyLast: $onlyLast, depth: $depth, maxDepth: $maxDepth));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\UtilDataProvider::dataProviderDistanceBetweenPoints
     */
    public function testDistanceBetweenPoints(float $latitude1, float $longitude1, float $latitude2, float $longitude2, bool $km, float $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Util())->distanceBetweenPoints(latitude1: $latitude1, longitude1: $longitude1, latitude2: $latitude2, longitude2: $longitude2, km: $km));
    }
}
