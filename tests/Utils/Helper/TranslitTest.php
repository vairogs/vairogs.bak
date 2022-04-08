<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils\Helper;

use PHPUnit\Framework\TestCase;
use Vairogs\Utils\Helper\Translit;

class TranslitTest extends TestCase
{
    /**
     * @dataProvider dataProvidertCyrillicToLatin
     */
    public function testCyrillicToLatin(string $string, string $expected, string $notExpected): void
    {
        $this->assertSame(expected: $expected, actual: Translit::cyrillicToLatin(text: $string));
        $this->assertNotSame(expected: $notExpected, actual: Translit::cyrillicToLatin(text: $string));
    }

    /**
     * @dataProvider dataProvidertLatinToCyrillic
     */
    public function testLatinToCyrillic(string $string, string $expected, string $notExpected): void
    {
        $this->assertSame(expected: $expected, actual: Translit::latinToCyrillic(text: $string));
        $this->assertNotSame(expected: $notExpected, actual: Translit::latinToCyrillic(text: $string));
    }

    public function dataProvidertCyrillicToLatin(): array
    {
        return [
            ['юнит-тест', 'yunit-tyest', 'unit-test'],
            ['интеграция', 'intyegratsiya', 'integratsiya'],
        ];
    }

    public function dataProvidertLatinToCyrillic(): array
    {
        return [
            ['yunit-tyest', 'юнит-тест', 'юнт-тест'],
            ['intyegratsiya', 'интеграция', 'интеграця'],
        ];
    }
}
