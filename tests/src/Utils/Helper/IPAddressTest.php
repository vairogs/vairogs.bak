<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils\Helper;

use PHPUnit\Framework\TestCase;
use Vairogs\Utils\Helper\IPAddress;

class IPAddressTest extends TestCase
{
    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\IpAddressDataProvider::dataProviderGetCIDRRange
     */
    public function testGetCIDRRange(string $cidr, bool $int, ?array $expected): void
    {
        $this->assertEquals(expected: $expected, actual: IPAddress::getCIDRRange(cidr: $cidr, int: $int));
    }
}
