<?php declare(strict_types = 1);

namespace Vairogs\Functions\Tests;

use Vairogs\Core\Tests\VairogsTestCase;
use Vairogs\Functions\Util;

class UtilTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\UtilDataProvider::dataProviderIsPrime
     */
    public function testIsPrime(int $number, bool $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Util())->isPrime(number: $number));
    }

    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\UtilDataProvider::dataProviderIsPrime
     */
    public function testIsPrimeNoGMP(int $number, bool $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Util())->isPrime(number: $number, override: true));
    }

    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\UtilDataProvider::dataProviderIsPrimeBelow1000
     */
    public function testIsPrimeBelow1000(int $number, ?bool $expectedBelow): void
    {
        $this->assertEquals(expected: $expectedBelow, actual: (new Util())->isPrimeBelow1000(number: $number));
    }

    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\UtilDataProvider::dataProviderDistanceBetweenPoints
     */
    public function testDistanceBetweenPoints(float $latitude1, float $longitude1, float $latitude2, float $longitude2, bool $km, float $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Util())->distanceBetweenPoints(latitude1: $latitude1, longitude1: $longitude1, latitude2: $latitude2, longitude2: $longitude2, km: $km));
    }
}
