<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils\Helper;

use PHPUnit\Framework\TestCase;
use Vairogs\Utils\Helper\Validate;

class ValidateTest extends TestCase
{
    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\ValidateDataProvider::dataProviderValidateEmail
     */
    public function testValidateEmail(string $email, bool $expected): void
    {
        $this->assertSame(expected: $expected, actual: Validate::validateEmail(email: $email));
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\ValidateDataProvider::dataProviderValidateIP
     */
    public function testValidateIP(string $ip, bool $deny, bool $expected): void
    {
        $this->assertEquals(expected: $expected, actual: Validate::validateIP(ip: $ip, deny: $deny));
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\ValidateDataProvider::dataProviderValidateCIDR
     */
    public function testValidateCIDR(string $cidr, bool $expected): void
    {
        $this->assertEquals(expected: $expected, actual: Validate::validateCIDR(cidr: $cidr));
    }
}
