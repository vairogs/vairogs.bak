<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils;

use PHPUnit\Framework\TestCase;
use Vairogs\Component\Utils\Uri;

class UriTest extends TestCase
{
    private const HEADER = 'Server: Apache';
    private const HEADER_ARR = ['Server' => 'Apache'];
    private const HEADERS = "Content-Length: 9328\nContent-Type: text/html";
    private const HEADERS_ARR = [
        'Content-Length' => '9328',
        'Content-Type' => 'text/html',
    ];
    private const VALID_URL = 'https://www.vairogs.com';
    private const INVALID_URL = '//oos,skds';
    private const VALID_URL_SHORT = 'vairogs.com';

    public function testParseHeaders(): void
    {
        $this->assertSame(self::HEADER_ARR, Uri::parseHeaders(self::HEADER));
        $this->assertSame(self::HEADERS_ARR, Uri::parseHeaders(self::HEADERS));
    }

    public function testIsUrl(): void
    {
        $this->assertTrue(Uri::isUrl(self::VALID_URL));
        $this->assertFalse(Uri::isUrl(self::VALID_URL_SHORT));
        $this->assertFalse(Uri::isUrl(self::INVALID_URL));
    }
}
