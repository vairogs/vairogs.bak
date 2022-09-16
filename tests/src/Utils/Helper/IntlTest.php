<?php declare(strict_types = 1);

namespace Vairogs\Tests\Source\Utils\Helper;

use Vairogs\Tests\Assets\VairogsTestCase;
use Vairogs\Utils\Helper\Intl;

class IntlTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\IntlDataProvider::dataProviderCyrillicToLatin
     */
    public function testCyrillicToLatin(string $string, string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Intl())->cyrillic2latin(text: $string));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\IntlDataProvider::dataProviderLatinToCyrillic
     */
    public function testLatinToCyrillic(string $string, string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Intl())->latin2cyrillic(text: $string));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\IntlDataProvider::dataProviderGetCountryName
     */
    public function testGetCountryName(string $country, string $locale, string $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Intl())->getCountryName(country: $country, locale: $locale));
    }
}
