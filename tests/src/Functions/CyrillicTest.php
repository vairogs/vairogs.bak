<?php declare(strict_types = 1);

namespace Vairogs\Tests\Source\Functions;

use Vairogs\Functions\Cyrillic;
use Vairogs\Tests\Assets\VairogsTestCase;

class CyrillicTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Tests\Assets\Functions\CyrillicDataProvider::dataProviderCyrillicToLatin
     */
    public function testCyrillicToLatin(string $string, string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Cyrillic())->cyrillic2latin(text: $string));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Functions\CyrillicDataProvider::dataProviderLatinToCyrillic
     */
    public function testLatinToCyrillic(string $string, string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Cyrillic())->latin2cyrillic(text: $string));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Functions\CyrillicDataProvider::dataProviderGetCountryName
     */
    public function testGetCountryName(string $country, string $locale, string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Cyrillic())->getCountryName(country: $country, locale: $locale));
    }
}
