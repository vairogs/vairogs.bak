<?php declare(strict_types = 1);

namespace Vairogs\Tests\Assets\Utils\Helper;

use Vairogs\Extra\Constants\Enum\Order;

class OrderDataProvider
{
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
