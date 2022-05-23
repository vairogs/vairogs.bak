<?php declare(strict_types = 1);

namespace Vairogs\Tests\Assets\Utils\Helper;

use Vairogs\Utils\Helper\Iteration;

class IterationDataProvider
{
    public function dataProviderIsEmpty(): array
    {
        return [
            [0, true, ],
            [[], true, ],
            [[[], ], true, ],
            [[['', ], ], true, ],
            [['vairogs', ], false, ],
        ];
    }

    public function dataProviderMakeMultiDimensional(): array
    {
        return [
            [[], [], ],
            [['vairogs', ], [['vairogs', ], ], ],
            [[['vairogs', ]], [['vairogs', ], ], ],
        ];
    }

    public function dataProviderUniqueMap(): array
    {
        return [
            [['vairogs', 'test', 'vairogs', ], ['vairogs', 'test', ], ],
            [['vairogs', 'test', ], ['vairogs', 'test', ], ],
            [[['vairogs', ], ['test', ], ['vairogs', ], ], [['vairogs', ], ['test', ], ], ],
        ];
    }

    public function dataProviderUnique(): array
    {
        return [
            [['vairogs', 'test', 'vairogs', ], ['vairogs', 'test', ], true, ],
            [['vairogs', 'test', ], ['vairogs', 'test', ], false, ],
            [[['vairogs', ], ['test', ], ['vairogs', ], ], [['vairogs', ], ['test', ], ['vairogs', ], ], false, ],
        ];
    }

    public function dataProviderArrayIntersectKeyRecursive(): array
    {
        return [
            [[1 => 'test', 2 => 'data', ], ['test', 'test2', ], [1 => 'test', ], ],
            [[['test', ], 'test2', ], [['test', ], 'test2', ], [['test', ], 'test2', ], ],
        ];
    }

    public function dataProviderArrayFlipRecursive(): array
    {
        return [
            [[1 => 'a', 2 => 'b', 'c' => 3, ], ['a' => 1, 'b' => 2, 3 => 'c', ], ],
            [['1' => new Iteration(), 2 => 'test', ], [1 => new Iteration(), 'test' => 2, ], ],
        ];
    }
}
