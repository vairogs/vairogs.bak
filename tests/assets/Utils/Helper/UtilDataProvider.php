<?php declare(strict_types = 1);

namespace Vairogs\Assets\Utils\Helper;

use const PHP_INT_MAX;

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

    public function dataProviderMakeOneDimension(): array
    {
        $array = [
            'vairogs' => [
                'cache' => [
                    'enabled' => true,
                ],
            ],
        ];

        return [
            [
                $array, false, 0, PHP_INT_MAX,
                [
                    'vairogs.cache.enabled' => true,
                    'vairogs.cache' => [
                        'enabled' => true,
                    ],
                    'vairogs' => [
                        'cache' => [
                            'enabled' => true,
                        ],
                    ],
                ],
            ],
            [
                $array, true, 1, PHP_INT_MAX,
                [
                    'vairogs.cache.enabled' => true,
                ],
            ],
            [
                $array, false, 0, 0, $array,
            ],
        ];
    }
}