<?php declare(strict_types = 1);

namespace Vairogs\Tests\Assets\Utils\Helper;

use Vairogs\Extra\Constants\Enum\Order;

class SortDataProvider
{
    public function dataProviderSwap(): array
    {
        return [
            [1, 2, ],
            [2, 3, ],
            [5, 5, ],
        ];
    }

    public function dataProviderBubbleSort(): array
    {
        return [
            [[7, 3, 1, 8, 0, 5, 4, 2, 9, 6, ], [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, ], ],
        ];
    }

    public function dataProviderSort(): array
    {
        return [
            [[['test' => 1, 'data' => 2, ], ['test' => 3, 'data' => 4, ], ['test' => 2, 'data' => 5, ], ], 'test', Order::ASC, [['test' => 1, 'data' => 2, ], ['test' => 2, 'data' => 5, ], ['test' => 3, 'data' => 4, ], ], ],
            [[['test' => 1, 'data' => 2, ], ['test' => 3, 'data' => 4, ], ['test' => 2, 'data' => 5, ], ], 'test', Order::DESC, [['test' => 3, 'data' => 4, ], ['test' => 2, 'data' => 5, ], ['test' => 1, 'data' => 2, ], ], ],
            [[['test' => 1, 'data' => 2, ], ['test' => 1, 'data' => 4, ], ['test' => 2, 'data' => 5, ], ], 'test', Order::ASC, [['test' => 1, 'data' => 2, ], ['test' => 1, 'data' => 4, ], ['test' => 2, 'data' => 5, ], ], ],
            [[['test' => 3, 'data' => 4, ], ], 'test', Order::DESC, [['test' => 3, 'data' => 4, ], ], ],
        ];
    }

    public function dataProviderSortException(): array
    {
        return [
            [[['test' => 1, 'data' => 2, ], ['test' => 3, 'data' => 4, ], ['test' => 2, 'data' => 5, ], ], 'value', Order::DESC, [['test' => 3, 'data' => 4, ], ['test' => 2, 'data' => 5, ], ['test' => 1, 'data' => 2, ], ], ],
        ];
    }
}
