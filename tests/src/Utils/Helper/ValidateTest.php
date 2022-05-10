<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils\Helper;

use Vairogs\Assets\VairogsTestCase;
use Vairogs\Utils\Helper\Validate;

class ValidateTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\ValidateDataProvider::dataProviderValidateEmail
     */
    public function testValidateEmail(string $email, bool $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Validate())->validateEmail(email: $email));
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\ValidateDataProvider::dataProviderValidateIP
     */
    public function testValidateIP(string $ip, bool $deny, bool $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Validate())->validateIPAddress(ipAddress: $ip, deny: $deny));
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\ValidateDataProvider::dataProviderValidateCIDR
     */
    public function testValidateCIDR(string $cidr, bool $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Validate())->validateCIDR(cidr: $cidr));
    }
}
