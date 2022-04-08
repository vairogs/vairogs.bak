<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils\Helper;

use PHPUnit\Framework\TestCase;
use Vairogs\Utils\Helper\Translit;

class TranslitTest extends TestCase
{
    /**
     * @dataProvider dataProvidertCyrillicToLatin
     */
    public function testCyrillicToLatin(string $string, string $expected): void
    {
        $this->assertSame(expected: $expected, actual: Translit::cyrillicToLatin(text: $string));
    }

    /**
     * @dataProvider dataProvidertLatinToCyrillic
     */
    public function testLatinToCyrillic(string $string, string $expected): void
    {
        $this->assertSame(expected: $expected, actual: Translit::latinToCyrillic(text: $string));
    }

    public function dataProvidertCyrillicToLatin(): array
    {
        return [
            ['юнит-тест', 'yunit-tyest'],
            ['интеграция', 'intyegratsiya'],
        ];
    }

    public function dataProvidertLatinToCyrillic(): array
    {
        return [
            ['yunit-tyest', 'юнит-тест'],
            ['intyegratsiya', 'интеграция'],
        ];
    }
}
