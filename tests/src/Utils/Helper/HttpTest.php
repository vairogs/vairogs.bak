<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils\Helper;

use Symfony\Component\HttpFoundation\Request;
use Vairogs\Assets\VairogsTestCase;
use Vairogs\Utils\Helper\Http;

class HttpTest extends VairogsTestCase
{
    public function testIsIE(): void
    {
        $this->assertFalse(condition: (new Http())->isIE(request: Request::create(uri: 'https://ip.vairogs.com/')));
    }

    public function testGetRequestMethods(): void
    {
        $this->assertIsArray(actual: $methods = (new Http())->getRequestMethods());
        $this->assertContains(needle: Request::METHOD_POST, haystack: $methods);
    }

    public function testGetRequestIdentity(): void
    {
        $request = Request::create(uri: 'https://ip.vairogs.com/');
        $request->initialize();
        $this->assertIsArray(actual: $result = (new Http())->getRequestIdentity(request: $request));
    }
}
