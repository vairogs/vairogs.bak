<?php declare(strict_types = 1);

namespace Vairogs\Tests\Assets\Utils\Helper;

class IdentificationDataProvider
{
    public function dataProviderValidatePersonCode(): array
    {
        return [
            ['150193-10933', true, ],
            ['11111', false, ],
            ['323232', false, ],
            ['320511-36626', true, ],
            ['320312-34357', false, ],
        ];
    }

    public function dataProviderHash(): array
    {
        return [
            ['vairogs', true, 'vairogs', ],
            ['', true, '', ],
            ['vairogs', false, 'vairogs2', ],
        ];
    }
}
