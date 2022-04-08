<?php declare(strict_types = 1);

namespace Vairogs\Assets\Utils\Helper;

class EmailDataProvider
{
    public function dataProviderIsValid(): array
    {
        return [
            ['vairogs@vairogs.com',  true],
            ['vairogs',             false],
            ['vairogs@vairogs',     false],
            ['vairogs@vairogs.123', false],
            ['',                    false],
        ];
    }
}
