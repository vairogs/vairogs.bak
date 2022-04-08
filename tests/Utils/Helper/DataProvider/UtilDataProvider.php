<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils\Helper\DataProvider;

class UtilDataProvider
{
    public function dataProviderIsPrime(): array
    {
        return [
            [2,    true,   true],
            [3,    true,   true],
            [5,    true,   true],
            [43,   true,   true],
            [367,  true,   true],
            [3019, true,   null],
            [1,    false, false],
            [4,    false, false],
            [10,   false, false],
            [32,   false, false],
            [360,  false, false],
            [3182, false,  null],
        ];
    }
}
