<?php declare(strict_types = 1);

namespace Vairogs\Functions\Tests;

use Vairogs\Core\Tests\VairogsTestCase;
use Vairogs\Functions\Cyrillic;

class CyrillicTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\CyrillicDataProvider::dataProviderCyrillicToLatin
     */
    public function testCyrillicToLatin(string $string, string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Cyrillic())->cyrillic2latin(text: $string));
    }

    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\CyrillicDataProvider::dataProviderLatinToCyrillic
     */
    public function testLatinToCyrillic(string $string, string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Cyrillic())->latin2cyrillic(text: $string));
    }

    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\CyrillicDataProvider::dataProviderGetCountryName
     */
    public function testGetCountryName(string $country, string $locale, string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Cyrillic())->getCountryName(country: $country, locale: $locale));
    }
}
