<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils\Helper;

use PHPUnit\Framework\TestCase;
use Vairogs\Utils\Helper\Util;
use function function_exists;

class UtilTest extends TestCase
{
    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\UtilDataProvider::dataProviderIsPrime
     */
    public function testIsPrime(int $number, bool $expected, ?bool $expectedBelow): void
    {
        $this->assertEquals(expected: $expected, actual: Util::isPrime(number: $number));
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\UtilDataProvider::dataProviderIsPrime
     */
    public function testIsPrimeNoGMP(int $number, bool $expected, ?bool $expectedBelow): void
    {
        if (function_exists(function: 'runkit7_function_remove')) {
            @runkit7_function_remove(function_name: 'gmp_prob_prime');
        }

        $this->assertEquals(expected: $expected, actual: Util::isPrime(number: $number));
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\UtilDataProvider::dataProviderIsPrime
     */
    public function testIsPrimeBelow1000(int $number, bool $expected, ?bool $expectedBelow): void
    {
        $this->assertEquals(expected: $expectedBelow, actual: Util::isPrimeBelow1000(number: $number));
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\UtilDataProvider::dataProviderMakeOneDimension
     */
    public function testMakeOneDimension(array $input, bool $onlyLast, int $depth, int $maxDepth, array $expected): void
    {
        $this->assertEquals($expected, Util::makeOneDimension(array: $input, onlyLast: $onlyLast, depth: $depth, maxDepth: $maxDepth));
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\UtilDataProvider::dataProviderGetCIDRRange
     */
    public function testGetCIDRRange(string $cidr, bool $int, ?array $expected): void
    {
        $this->assertEquals(expected: $expected, actual: Util::getCIDRRange(cidr: $cidr, int: $int));
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\UtilDataProvider::dataProviderDistanceBetweenPoints
     */
    public function testDistanceBetweenPoints(float $latitude1, float $longitude1, float $latitude2, float $longitude2, bool $km, float $expected): void
    {
        $this->assertEquals(expected: $expected, actual: Util::distanceBetweenPoints(latitude1: $latitude1, latitude2: $latitude2, longitude1: $longitude1, longitude2: $longitude2, km: $km));
    }
}
