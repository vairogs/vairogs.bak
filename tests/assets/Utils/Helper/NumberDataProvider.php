<?php declare(strict_types = 1);

namespace Vairogs\Assets\Utils\Helper;

class NumberDataProvider
{
    public function dataProviderGreatestCommonDivisor(): array
    {
        return [
            [30, 24, 6],
            [75, 15, 15],
        ];
    }

    public function dataProviderLeastCommonMultiple(): array
    {
        return [
            [12, 15, 60],
            [12, 75, 300],
        ];
    }

    public function dataProviderIsIntFloat(): array
    {
        return [
            [1, true],
            [128, true],
            [0, true],
            [128.5, false],
        ];
    }
}
