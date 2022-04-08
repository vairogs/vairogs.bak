<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils\Helper\DataProvider;

class TranslitDataProvider
{
    public function dataProvidertCyrillicToLatin(): array
    {
        return [
            ['юнит-тест',    'yunit-tyest'],
            ['интеграция', 'intyegratsiya'],
        ];
    }

    public function dataProvidertLatinToCyrillic(): array
    {
        return [
            ['yunit-tyest',    'юнит-тест'],
            ['intyegratsiya', 'интеграция'],
        ];
    }
}
