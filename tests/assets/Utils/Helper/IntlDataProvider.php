<?php declare(strict_types = 1);

namespace Vairogs\Assets\Utils\Helper;

class IntlDataProvider
{
    public function dataProviderCyrillicToLatin(): array
    {
        return [
            ['юнит-тест',    'yunit-tyest'],
            ['интеграция', 'intyegratsiya'],
        ];
    }

    public function dataProviderLatinToCyrillic(): array
    {
        return [
            ['yunit-tyest',    'юнит-тест'],
            ['intyegratsiya', 'интеграция'],
        ];
    }

    public function dataProviderGetCountryName(): array
    {
        return [
            ['LV', 'en', 'Latvia'],
            ['lv', 'lv', 'Latvija'],
        ];
    }
}
