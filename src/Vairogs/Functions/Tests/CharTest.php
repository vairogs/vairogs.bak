<?php declare(strict_types = 1);

namespace Vairogs\Functions\Tests;

use Vairogs\Core\Tests\VairogsTestCase;
use Vairogs\Functions\Char;

class CharTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\CharDataProvider::dataProviderSanitizeFloat
     */
    public function testSanitizeFloat(string $string, float $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Char())->sanitizeFloat(string: $string));
    }

    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\CharDataProvider::dataProviderToSnakeCase
     */
    public function testToSnakeCase(string $string, bool $skip, string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Char())->toSnakeCase(string: $string, skipCamel: $skip));
    }

    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\CharDataProvider::dataProviderFromCamelCase
     */
    public function testFromCamelCase(string $string, string $sep, string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Char())->fromCamelCase(string: $string, separator: $sep));
    }

    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\CharDataProvider::dataProviderToCamelCaseLCFirst
     */
    public function testToCamelCaseLCFirst(string $input, string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Char())->toCamelCaseLCFisrt(string: $input));
    }

    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\CharDataProvider::dataProviderToCamelCaseUCFirst
     */
    public function testToCamelCaseUCFirst(string $input, string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Char())->toCamelCaseLCFisrtUCFirst(string: $input));
    }
}
