<?php declare(strict_types = 1);

namespace Vairogs\Functions\Tests\DataProvider;

class SortDataProvider
{
    public static function dataProviderSwap(): array
    {
        return [
            [1, 2, ],
            [2, 3, ],
            [5, 5, ],
        ];
    }

    public static function dataProviderBubbleSort(): array
    {
        return [
            [[7, 3, 1, 8, 0, 5, 4, 2, 9, 6, ], [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, ], ],
        ];
    }
}
