<?php declare(strict_types = 1);

namespace Vairogs\Tests\Assets\Utils\Helper;

class PhpDataProvider
{
    public function dataProviderBoolval(): array
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

    public function dataProviderGetterSetter(): array
    {
        return [
            ['aaa', 'getAaa', 'setAaa', ],
            ['oneTwo', 'getOneTwo', 'setOneTwo', ],
            ['1one', 'get1one', 'set1one', ],
        ];
    }
}
