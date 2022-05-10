<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils\Helper;

use Vairogs\Assets\VairogsTestCase;
use Vairogs\Utils\Helper\Char;

class CharTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\CharDataProvider::dataProviderSanitizeFloat
     */
    public function testSanitizeFloat(string $string, float $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Char())->sanitizeFloat(string: $string));
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\CharDataProvider::dataProviderToSnakeCase
     */
    public function testToSnakeCase(string $string, bool $skip, string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Char())->toSnakeCase(string: $string, skipCamel: $skip));
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\CharDataProvider::dataProviderFromCamelCase
     */
    public function testFromCamelCase(string $string, string $sep, string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Char())->fromCamelCase(string: $string, separator: $sep));
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\CharDataProvider::dataProviderBase62
     */
    public function testBase62(int $number): void
    {
        $this->assertEquals(expected: $number, actual: (new Char())->base62Decode(string: (new Char())->base62Encode(number: $number)));
    }
}
