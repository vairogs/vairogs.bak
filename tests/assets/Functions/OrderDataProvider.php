<?php declare(strict_types = 1);

namespace Vairogs\Tests\Assets\Functions;

use Doctrine\Common\Collections\Criteria;

class OrderDataProvider
{
    public static function dataProviderSort(): array
    {
        return [
            [[['test' => 1, 'data' => 2, ], ['test' => 3, 'data' => 4, ], ['test' => 2, 'data' => 5, ], ], 'test', Criteria::ASC, [['test' => 1, 'data' => 2, ], ['test' => 2, 'data' => 5, ], ['test' => 3, 'data' => 4, ], ], ],
            [[['test' => 1, 'data' => 2, ], ['test' => 3, 'data' => 4, ], ['test' => 2, 'data' => 5, ], ], 'test', Criteria::DESC, [['test' => 3, 'data' => 4, ], ['test' => 2, 'data' => 5, ], ['test' => 1, 'data' => 2, ], ], ],
            [[['test' => 1, 'data' => 2, ], ['test' => 1, 'data' => 4, ], ['test' => 2, 'data' => 5, ], ], 'test', Criteria::ASC, [['test' => 1, 'data' => 2, ], ['test' => 1, 'data' => 4, ], ['test' => 2, 'data' => 5, ], ], ],
            [[['test' => 3, 'data' => 4, ], ], 'test', Criteria::DESC, [['test' => 3, 'data' => 4, ], ], ],
        ];
    }

    public static function dataProviderSortException(): array
    {
        return [
            [[['test' => 1, 'data' => 2, ], ['test' => 3, 'data' => 4, ], ['test' => 2, 'data' => 5, ], ], 'value', Criteria::DESC, [['test' => 3, 'data' => 4, ], ['test' => 2, 'data' => 5, ], ['test' => 1, 'data' => 2, ], ], ],
        ];
    }
}
