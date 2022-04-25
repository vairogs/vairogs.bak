<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils\Helper;

use PHPUnit\Framework\TestCase;
use Vairogs\Utils\Helper\Intl;

class IntlTest extends TestCase
{
    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\IntlDataProvider::dataProviderCyrillicToLatin
     */
    public function testCyrillicToLatin(string $string, string $expected): void
    {
        $this->assertSame(expected: $expected, actual: Intl::cyrillicToLatin(text: $string));
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\IntlDataProvider::dataProviderLatinToCyrillic
     */
    public function testLatinToCyrillic(string $string, string $expected): void
    {
        $this->assertSame(expected: $expected, actual: Intl::latinToCyrillic(text: $string));
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\IntlDataProvider::dataProviderGetCountryName
     */
    public function testGetCountryName(string $country, string $locale, string $expected): void
    {
        $this->assertSame(expected: $expected, actual: Intl::getCountryName(country: $country, locale: $locale));
    }
}
