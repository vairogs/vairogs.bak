<?php declare(strict_types = 1);

namespace Vairogs\Assets\Utils\Helper;

class JsonDataProvider
{
    public function dataProviderJson(): array
    {
        return [
            ['vairogs', 0],
            [['vairogs'], 0],
        ];
    }
}
