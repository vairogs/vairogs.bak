<?php declare(strict_types = 1);

namespace Vairogs\Functions\Tests\DataProvider;

class PhpDataProvider
{
    public static function dataProviderBoolval(): array
    {
        return [
            [true, true, ],
            ['false', false, ],
            [null, false, ],
            ['aaa', false, ],
            ['Y', true, ],
            ['n', false, ],
        ];
    }

    public static function dataProviderGetterSetter(): array
    {
        return [
            ['aaa', 'getAaa', 'setAaa', ],
            ['oneTwo', 'getOneTwo', 'setOneTwo', ],
            ['1one', 'get1one', 'set1one', ],
        ];
    }
}
