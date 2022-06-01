<?php declare(strict_types = 1);

namespace Vairogs\Tests\Source\Utils\Helper;

use Vairogs\Tests\Assets\VairogsTestCase;
use Vairogs\Utils\Helper\Validate;

class ValidateTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\ValidateDataProvider::dataProviderValidateEmail
     */
    public function testValidateEmail(string $email, bool $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Validate())->validateEmail(email: $email));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\ValidateDataProvider::dataProviderValidateIP
     */
    public function testValidateIP(string $ip, bool $deny, bool $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Validate())->validateIPAddress(ipAddress: $ip, deny: $deny));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\ValidateDataProvider::dataProviderValidateCIDR
     */
    public function testValidateCIDR(string $cidr, bool $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Validate())->validateCIDR(cidr: $cidr));
    }
}
