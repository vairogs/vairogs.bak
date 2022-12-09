<?php declare(strict_types = 1);

namespace Vairogs\Functions\Tests\DataProvider;

class UtilDataProvider
{
    public static function dataProviderIsPrime(): array
    {
        return [
            [2,    true,  ],
            [3,    true,  ],
            [5,    true,  ],
            [43,   true,  ],
            [367,  true,  ],
            [3019, true,  ],
            [1,    false, ],
            [4,    false, ],
            [10,   false, ],
            [32,   false, ],
            [360,  false, ],
            [3182, false, ],
        ];
    }

    public static function dataProviderIsPrimeBelow1000(): array
    {
        return [
            [2,    true,  ],
            [3,    true,  ],
            [5,    true,  ],
            [43,   true,  ],
            [367,  true,  ],
            [3019, null,  ],
            [1,    false, ],
            [4,    false, ],
            [10,   false, ],
            [32,   false, ],
            [360,  false, ],
            [3182, null,  ],
        ];
    }

    public static function dataProviderDistanceBetweenPoints(): array
    {
        return [
            [56.95633943847958, 24.197759089350726, 56.95457297448779, 24.200456656517165, true, 0.2556, ],
            [56.95633943847958, 24.197759089350726, 56.95457297448779, 24.200456656517165, false, 0.1588, ],
            [56.95633943847958, 24.197759089350726, 56.95633943847958, 24.197759089350726, true, 0.0, ],
        ];
    }
}
