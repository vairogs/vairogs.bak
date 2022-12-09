<?php declare(strict_types = 1);

namespace Vairogs\Functions\Tests\DataProvider;

class TimeDataProvider
{
    public static function dataProviderFormat(): array
    {
        return [
            [45, '45 seconds', false, ],
            [0.045, '45 micros', false, ],
            [3000, '50 minutes', false, ],
            [7200, '2 hours', false, ],
            [5400, '1 hour 30 minutes', false, ],
            [45, ['second' => 45, ], true, ],
            [0.045, ['micro' => 45, ], true, ],
            [3000, ['minute' => 50, ], true, ],
            [7200, ['hour' => 2, ], true, ],
            [5400, ['hour' => 1, 'minute' => 30, ], true, ],
        ];
    }
}
