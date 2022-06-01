<?php declare(strict_types = 1);

namespace Vairogs\Tests\Source\Utils\Helper;

use Vairogs\Tests\Assets\VairogsTestCase;
use Vairogs\Utils\Helper\Char;

class CharTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\CharDataProvider::dataProviderSanitizeFloat
     */
    public function testSanitizeFloat(string $string, float $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Char())->sanitizeFloat(string: $string));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\CharDataProvider::dataProviderToSnakeCase
     */
    public function testToSnakeCase(string $string, bool $skip, string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Char())->toSnakeCase(string: $string, skipCamel: $skip));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\CharDataProvider::dataProviderFromCamelCase
     */
    public function testFromCamelCase(string $string, string $sep, string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Char())->fromCamelCase(string: $string, separator: $sep));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\CharDataProvider::dataProviderToCamelCase
     */
    public function testToCamelCase(string $input, string $function, string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Char())->toCamelCase(string: $input, function: $function));
    }
}
