<?php declare(strict_types = 1);

namespace Vairogs\Functions\Tests;

use Symfony\Component\HttpFoundation\Request;
use Vairogs\Core\Tests\VairogsTestCase;
use Vairogs\Functions\Constants\Definition;
use Vairogs\Functions\Constants\Http;
use Vairogs\Functions\IPAddress;

class IPAddressTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\IPAddressDataProvider::dataProviderGetCIDRRange
     */
    public function testGetCIDRRange(string $cidr, bool $int, ?array $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new IPAddress())->getCIDRRange(cidr: $cidr, int: $int));
    }

    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\IPAddressDataProvider::dataProviderGetRemoteIpCF
     */
    public function testGetRemoteIpCF(?string $ipHeader, ?string $ipCF, bool $trust, string $expected): void
    {
        $request = Request::create(uri: Definition::IDENT);

        if (null !== $ipHeader) {
            $request->server->set(key: Http::HTTP_X_REAL_IP, value: $ipHeader);
        }

        if (null !== $ipCF) {
            $request->server->set(key: Http::HTTP_CF_CONNECTING_IP, value: $ipCF);
        }

        $this->assertEquals(expected: $expected, actual: (new IPAddress())->getRemoteIpCF(request: $request, trust: $trust));
    }
}
