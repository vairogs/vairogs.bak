<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Tests;

use PHPUnit\Framework\TestCase;
use Vairogs\Component\Utils\Util;

class UtilTest extends TestCase
{
    public function testIsPrime(): void
    {
        $this->assertTrue(Util::isPrime(67));
        $this->assertFalse(Util::isPrime(68));
    }
}
