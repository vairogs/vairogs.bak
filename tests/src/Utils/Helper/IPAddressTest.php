<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils\Helper;

use Symfony\Component\HttpFoundation\Request;
use Vairogs\Assets\VairogsTestCase;
use Vairogs\Extra\Constants\Http;
use Vairogs\Utils\Helper\IPAddress;

class IPAddressTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\IpAddressDataProvider::dataProviderGetCIDRRange
     */
    public function testGetCIDRRange(string $cidr, bool $int, ?array $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new IPAddress())->getCIDRRange(cidr: $cidr, int: $int));
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\IpAddressDataProvider::dataProviderGetRemoteIpCF
     */
    public function testGetRemoteIpCF(?string $ipHeader, ?string $ipCF, bool $trust, string $expected): void
    {
        $request = Request::create(uri: 'https://ip.vairogs.com/');

        if (null !== $ipHeader) {
            $request->server->set(key: Http::HTTP_X_REAL_IP, value: $ipHeader);
        }

        if (null !== $ipCF) {
            $request->server->set(key: Http::HTTP_CF_CONNECTING_IP, value: $ipCF);
        }

        $this->assertEquals(expected: $expected, actual: (new IPAddress())->getRemoteIpCF(request: $request, trust: $trust));
    }
}
