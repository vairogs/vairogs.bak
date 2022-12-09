<?php declare(strict_types = 1);

namespace Vairogs\Functions\Tests;

use Vairogs\Core\Tests\VairogsTestCase;
use Vairogs\Functions\Validate;

class ValidateTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\ValidateDataProvider::dataProviderValidateEmail
     */
    public function testValidateEmail(string $email, bool $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Validate())->validateEmail(email: $email));
    }

    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\ValidateDataProvider::dataProviderValidateIP
     */
    public function testValidateIP(string $ip, bool $deny, bool $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Validate())->validateIPAddress(ipAddress: $ip, deny: $deny));
    }

    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\ValidateDataProvider::dataProviderValidateCIDR
     */
    public function testValidateCIDR(string $cidr, bool $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Validate())->validateCIDR(cidr: $cidr));
    }
}
