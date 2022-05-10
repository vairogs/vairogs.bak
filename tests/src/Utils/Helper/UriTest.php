<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils\Helper;

use Symfony\Component\HttpFoundation\Request;
use Vairogs\Assets\VairogsTestCase;
use Vairogs\Utils\Helper\Uri;

class UriTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\UriDataProvider::dataProviderArrayFromQueryString
     */
    public function testArrayFromQueryString(string $query, array $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Uri())->arrayFromQueryString(query: $query));
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\UriDataProvider::dataProviderGetSchema
     */
    public function testGetSchema(string $url, string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Uri())->getSchema(request: Request::create(uri: $url)));
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\UriDataProvider::dataUrlEncode
     */
    public function testUrlEncode(string $url): void
    {
        $this->assertEquals(expected: $url, actual: (new Uri())->urlEncode(url: $url));
    }
}
