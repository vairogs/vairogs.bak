<?php declare(strict_types = 1);

namespace Vairogs\Tests\Source\Utils\Helper;

use Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Vairogs\Extra\Constants\Definition;
use Vairogs\Extra\Constants\Service;
use Vairogs\Tests\Assets\VairogsTestCase;
use Vairogs\Utils\Helper\Uri;

class UriTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\UriDataProvider::dataProviderArrayFromQueryString
     */
    public function testArrayFromQueryString(string $query, array $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Uri())->arrayFromQueryString(query: $query));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\UriDataProvider::dataProviderGetSchema
     */
    public function testGetSchema(string $url, string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Uri())->getSchema(request: Request::create(uri: $url)));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\UriDataProvider::dataUrlEncode
     */
    public function testUrlEncode(string $url): void
    {
        $this->assertEquals(expected: $url, actual: (new Uri())->urlEncode(url: $url));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\UriDataProvider::dataProviderRouteExists
     *
     * @throws Exception
     */
    public function testRouteExists(string $route, bool $expected): void
    {
        $this->assertInstanceOf(expected: RouterInterface::class, actual: $router = $this->container->get(id: Service::ROUTER, invalidBehavior: ContainerInterface::NULL_ON_INVALID_REFERENCE));
        /* @var RouterInterface $router */
        $this->assertEquals(expected: $expected, actual: (new Uri())->routeExists(router: $router, route: $route));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\UriDataProvider::dataProviderIsUrl
     */
    public function testIsUrl(string $url, bool $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Uri())->isUrl(url: $url));
    }

    public function testGetRawParseHeaders(): void
    {
        $headers = Request::create(uri: Definition::IDENT)->headers;
        $headers->set(key: 'test', values: null);
        $this->assertIsArray(actual: $headers = (new Uri())->parseHeaders(rawHeaders: (new Uri())->getRawHeaders(headerBag: $headers)));
        $this->assertArrayHasKey(key: 'user-agent', array: $headers);
        $this->assertEquals(expected: 'Symfony', actual: $headers['user-agent']);
    }
}
