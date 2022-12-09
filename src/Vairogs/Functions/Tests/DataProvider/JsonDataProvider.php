<?php declare(strict_types = 1);

namespace Vairogs\Functions\Tests\DataProvider;

class JsonDataProvider
{
    public static function dataProviderJson(): array
    {
        return [
            ['vairogs', 0, ],
            [['vairogs', ], 0, ],
        ];
    }
}
