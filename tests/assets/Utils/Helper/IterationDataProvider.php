<?php declare(strict_types = 1);

namespace Vairogs\Assets\Utils\Helper;

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
        ];
    }
}
