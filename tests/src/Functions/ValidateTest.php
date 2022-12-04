<?php declare(strict_types = 1);

namespace Vairogs\Tests\Source\Functions;

use Vairogs\Functions\Validate;
use Vairogs\Tests\Assets\VairogsTestCase;

class ValidateTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Tests\Assets\Functions\ValidateDataProvider::dataProviderValidateEmail
     */
    public function testValidateEmail(string $email, bool $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Validate())->validateEmail(email: $email));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Functions\ValidateDataProvider::dataProviderValidateIP
     */
    public function testValidateIP(string $ip, bool $deny, bool $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Validate())->validateIPAddress(ipAddress: $ip, deny: $deny));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Functions\ValidateDataProvider::dataProviderValidateCIDR
     */
    public function testValidateCIDR(string $cidr, bool $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Validate())->validateCIDR(cidr: $cidr));
    }
}
