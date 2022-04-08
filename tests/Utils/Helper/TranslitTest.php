<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils\Helper;

use PHPUnit\Framework\TestCase;
use Vairogs\Utils\Helper\Translit;

class TranslitTest extends TestCase
{
    /**
     * @dataProvider \Vairogs\Tests\Utils\Helper\DataProvider\TranslitDataProvider::dataProvidertCyrillicToLatin
     */
    public function testCyrillicToLatin(string $string, string $expected): void
    {
        $this->assertSame(expected: $expected, actual: Translit::cyrillicToLatin(text: $string));
    }

    /**
     * @dataProvider \Vairogs\Tests\Utils\Helper\DataProvider\TranslitDataProvider::dataProvidertLatinToCyrillic
     */
    public function testLatinToCyrillic(string $string, string $expected): void
    {
        $this->assertSame(expected: $expected, actual: Translit::latinToCyrillic(text: $string));
    }
}
