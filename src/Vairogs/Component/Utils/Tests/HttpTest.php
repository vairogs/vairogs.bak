<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Vairogs\Component\Utils\Http;

class HttpTest extends TestCase
{
    public function testGetSchema(): void
    {
        $httpRequest = Request::create('http://www.httpvshttps.com/', 'GET');
        $this->assertSame('http://', Http::getSchema($httpRequest));
        $httpsRequest = Request::create('https://www.httpvshttps.com/', 'GET');
        $this->assertSame('https://', Http::getSchema($httpsRequest));
    }

    public function testUseHttps(): void
    {
        $httpRequest = Request::create('http://www.httpvshttps.com/', 'GET');
        $this->assertFalse(Http::isHttps($httpRequest));
        $httpsRequest = Request::create('https://www.httpvshttps.com/', 'GET');
        $this->assertTrue(Http::isHttps($httpsRequest));
    }

    public function testIsAbsolute(): void
    {
        $this->assertTrue(Http::isAbsolute('https://google.lv/image.png'));
        $this->assertFalse(Http::isAbsolute('../image.png'));
    }
}
