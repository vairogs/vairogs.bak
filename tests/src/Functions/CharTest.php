<?php declare(strict_types = 1);

namespace Vairogs\Tests\Source\Functions;

use Vairogs\Functions\Char;
use Vairogs\Tests\Assets\VairogsTestCase;

class CharTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Tests\Assets\Functions\CharDataProvider::dataProviderSanitizeFloat
     */
    public function testSanitizeFloat(string $string, float $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Char())->sanitizeFloat(string: $string));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Functions\CharDataProvider::dataProviderToSnakeCase
     */
    public function testToSnakeCase(string $string, bool $skip, string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Char())->toSnakeCase(string: $string, skipCamel: $skip));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Functions\CharDataProvider::dataProviderFromCamelCase
     */
    public function testFromCamelCase(string $string, string $sep, string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Char())->fromCamelCase(string: $string, separator: $sep));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Functions\CharDataProvider::dataProviderToCamelCaseLCFirst
     */
    public function testToCamelCaseLCFirst(string $input, string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Char())->toCamelCaseLCFisrt(string: $input));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Functions\CharDataProvider::dataProviderToCamelCaseUCFirst
     */
    public function testToCamelCaseUCFirst(string $input, string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Char())->toCamelCaseLCFisrtUCFirst(string: $input));
    }
}
