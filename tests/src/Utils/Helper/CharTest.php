<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils\Helper;

use PHPUnit\Framework\TestCase;
use Vairogs\Utils\Helper\Char;

class CharTest extends TestCase
{
    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\CharDataProvider::dataProviderSanitizeFloat
     */
    public function testSanitizeFloat(string $string, float $expected): void
    {
        $this->assertSame(expected: $expected, actual: Char::sanitizeFloat(string: $string));
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\CharDataProvider::dataProviderToSnakeCase
     */
    public function testToSnakeCase(string $string, bool $skip, string $expected): void
    {
        $this->assertSame(expected: $expected, actual: Char::toSnakeCase(string: $string, skipCamel: $skip));
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\CharDataProvider::dataProviderFromCamelCase
     */
    public function testFromCamelCase(string $string, string $sep, string $expected): void
    {
        $this->assertSame(expected: $expected, actual: Char::fromCamelCase(string: $string, separator: $sep));
    }
}
