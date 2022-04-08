<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils\Helper;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Vairogs\Utils\Helper\Uri;

class UriTest extends TestCase
{
    /**
     * @dataProvider \Vairogs\Tests\Utils\Helper\DataProvider\UriDataProvider::dataProviderArrayFromQueryString
     */
    public function testArrayFromQueryString(string $query, array $expected): void
    {
        $this->assertSame(expected: $expected, actual: Uri::arrayFromQueryString(query: $query));
    }

    /**
     * @dataProvider \Vairogs\Tests\Utils\Helper\DataProvider\UriDataProvider::dataProviderGetSchema
     */
    public function testGetSchema(string $url, string $expected): void
    {
        $this->assertSame(expected: $expected, actual: Uri::getSchema(request: Request::create(uri: $url)));
    }

    /**
     * @dataProvider \Vairogs\Tests\Utils\Helper\DataProvider\UriDataProvider::dataUrlEncode
     */
    public function testUrlEncode(string $url): void
    {
        $this->assertSame(expected: $url, actual: Uri::urlEncode(url: $url));
    }
}
