<?php declare(strict_types = 1);

namespace Vairogs\Tests\Source\Functions;

use ReflectionException;
use Symfony\Component\HttpFoundation\Request;
use Vairogs\Functions\Constants\Definition;
use Vairogs\Functions\Http;
use Vairogs\Tests\Assets\VairogsTestCase;

class HttpTest extends VairogsTestCase
{
    public function testIsIE(): void
    {
        $this->assertFalse(condition: (new Http())->isIE(request: Request::create(uri: Definition::IDENT)));
    }

    public function testGetRequestMethods(): void
    {
        $this->assertIsArray(actual: $methods = (new Http())->getRequestMethods());
        $this->assertContains(needle: Request::METHOD_POST, haystack: $methods);
    }

    /**
     * @throws ReflectionException
     */
    public function testGetRequestIdentity(): void
    {
        $request = Request::create(uri: Definition::IDENT);
        $request->initialize();
        $this->assertIsArray(actual: (new Http())->getRequestIdentity(request: $request));
    }
}
