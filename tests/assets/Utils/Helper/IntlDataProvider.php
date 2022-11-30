<?php declare(strict_types = 1);

namespace Vairogs\Tests\Assets\Utils\Helper;

class IntlDataProvider
{
    public static function dataProviderCyrillicToLatin(): array
    {
        return [
            ['юнит-тест', 'yunit-tyest', ],
            ['интеграция', 'intyegratsiya', ],
        ];
    }

    public static function dataProviderLatinToCyrillic(): array
    {
        return [
            ['yunit-tyest', 'юнит-тест', ],
            ['intyegratsiya', 'интеграция', ],
        ];
    }

    public static function dataProviderGetCountryName(): array
    {
        return [
            ['LV', 'en', 'Latvia', ],
            ['lv', 'lv', 'Latvija', ],
        ];
    }
}
