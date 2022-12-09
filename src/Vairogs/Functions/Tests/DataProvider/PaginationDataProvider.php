<?php declare(strict_types = 1);

namespace Vairogs\Functions\Tests\DataProvider;

class PaginationDataProvider
{
    public static function dataProvider(): array
    {
        return [
            [7, 30, 1, -1, [1, 2, 3, -1, 28, 29, 30, ], ],
            [12, 30, 9, -1, [1, 2, 3, -1, 7, 8, 9, 10, 11, -1, 29, 30, ], ],
            [5, 10, 3, -1, [1, 2, 3, -1, 10, ], ],
            [5, 4, 3, -1, [1, 2, 3, 4, ], ],
        ];
    }

    public static function dataProviderException(): array
    {
        return [
            [4, 30, 1, -1, [1, 2, 3, -1, 28, 29, 30, ], ],
            [7, 0, 1, -1, [1, 2, 3, -1, 28, 29, 30, ], ],
            [7, 30, 0, -1, [1, 2, 3, -1, 28, 29, 30, ], ],
            [7, 5, 10, -1, [1, 2, 3, -1, 28, 29, 30, ], ],
            [7, 30, 1, 5, [1, 2, 3, -1, 28, 29, 30, ], ],
        ];
    }
}
