<?php declare(strict_types = 1);

namespace Vairogs\Assets\Utils\Handler;

use Vairogs\Utils\Helper\Iteration;
use Vairogs\Utils\Helper\Util;

class FunctionHandlerDataProvider
{
    public function dataProvider(): array
    {
        return [
            ['is_string', null, true, 'vairogs'],
            ['getIfNotEmpty', new Iteration(), null, [], 'vairogs'],
            ['isPrime', new Util(), true, 3],
        ];
    }
}
