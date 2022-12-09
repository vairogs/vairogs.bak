<?php declare(strict_types = 1);

namespace Vairogs\Functions\Tests\DataProvider;

class NumberDataProvider
{
    public static function dataProviderGreatestCommonDivisor(): array
    {
        return [
            [30, 24, 6, ],
            [75, 15, 15, ],
        ];
    }

    public static function dataProviderLeastCommonMultiple(): array
    {
        return [
            [12, 15, 60, ],
            [12, 75, 300, ],
        ];
    }

    public static function dataProviderIsIntFloat(): array
    {
        return [
            [1, true, ],
            [128, true, ],
            [0, true, ],
            [128.5, false, ],
        ];
    }

    public static function dataProviderEncryption(): array
    {
        return [
            ['Vairogs! 盾牌！ Štít', 'testVAIROGS789', ],
            ['', '123', ],
            ['Vairogs!', '123', ],
        ];
    }
}
