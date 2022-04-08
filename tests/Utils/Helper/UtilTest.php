<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils\Helper;

use PHPUnit\Framework\TestCase;
use Vairogs\Utils\Helper\Util;
use function function_exists;

class UtilTest extends TestCase
{
    /**
     * @dataProvider dataProviderIsPrime
     */
    public function testIsPrime(int $number, bool $expected, ?bool $expectedBelow): void
    {
        $this->assertSame(expected: $expected, actual: Util::isPrime(number: $number));
    }

    /**
     * @dataProvider dataProviderIsPrime
     */
    public function testIsPrimeNoGMP(int $number, bool $expected, ?bool $expectedBelow): void
    {
        if (function_exists(function: 'runkit7_function_remove')) {
            @runkit7_function_remove(function_name: 'gmp_prob_prime');
        }

        $this->assertSame(expected: $expected, actual: Util::isPrime(number: $number));
    }

    /**
     * @dataProvider dataProviderIsPrime
     */
    public function testIsPrimeBelow1000(int $number, bool $expected, ?bool $expectedBelow): void
    {
        $this->assertSame(expected: $expectedBelow, actual: Util::isPrimeBelow1000(number: $number));
    }

    public function dataProviderIsPrime(): array
    {
        return [
            [2,    true,   true],
            [3,    true,   true],
            [5,    true,   true],
            [43,   true,   true],
            [367,  true,   true],
            [3019, true,   null],
            [1,    false, false],
            [4,    false, false],
            [10,   false, false],
            [32,   false, false],
            [360,  false, false],
            [3182, false,  null],
        ];
    }
}
