<?php declare(strict_types = 1);

namespace Vairogs\Functions\Tests\DataProvider;

class ValidateDataProvider
{
    public static function dataProviderValidateEmail(): array
    {
        return [
            ['vairogs@vairogs.com',  true, ],
            ['vairogs', false, ],
            ['vairogs@vairogs', false, ],
            ['vairogs@vairogs.123', false, ],
            ['', false, ],
        ];
    }

    public static function dataProviderValidateIP(): array
    {
        return [
            ['127.0.0.1', false, true, ],
            ['127.0.0.1', true, false, ],
            ['192.168.1.254', false, true, ],
            ['192.168.1.254', true, false, ],
            ['10.0.0.0/24', false, false, ],
            ['vairogs', false, false, ],
        ];
    }

    public static function dataProviderValidateCIDR(): array
    {
        return [
            ['10.0.0.0/24', true, ],
            ['10.0.0.0/256', false, ],
            ['vairogs', false, ],
        ];
    }
}
