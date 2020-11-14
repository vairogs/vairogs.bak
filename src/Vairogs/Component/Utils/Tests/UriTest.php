<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Tests;

use PHPUnit\Framework\TestCase;
use Vairogs\Component\Utils\Helper\Uri;

class UriTest extends TestCase
{
    /**
     * @var string
     */
    private const HEADER = 'Server: Apache';

    /**
     * @var array
     */
    private const HEADER_ARR = ['Server' => 'Apache'];

    /**
     * @var string
     */
    private const HEADERS = "Content-Length: 9328\nContent-Type: text/html";

    /**
     * @var array
     */
    private const HEADERS_ARR = [
        'Content-Length' => '9328',
        'Content-Type' => 'text/html',
    ];

    /**
     * @var string
     */
    private const VALID_URL = 'https://www.vairogs.com';

    /**
     * @var string
     */
    private const INVALID_URL = '//oos,skds';

    /**
     * @var string
     */
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
