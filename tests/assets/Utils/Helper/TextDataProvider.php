<?php declare(strict_types = 1);

namespace Vairogs\Tests\Assets\Utils\Helper;

use Vairogs\Core\Vairogs;

class TextDataProvider
{
    public function dataProviderOneStripSpace(): array
    {
        return [
            ['hello  world vairogs', 'hello world vairogs', 'helloworldvairogs', ],
        ];
    }

    public function dataProviderLimit(): array
    {
        return [
            ['sapien nec sagittis aliquam malesuada bibendum arcu vitae elementum curabitur', 32, 4, '...', 'sapien nec sagittis aliquam male...', 'sapien nec sagittis aliquam...', 'sapien nec sagittis aliquam...', ],
            ['sapien nec sagittis', 32, 4, '...', 'sapien nec sagittis', 'sapien nec sagittis', 'sapien nec sagittis', ],
        ];
    }

    public function dataProviderGetLastPart(): array
    {
        return [
            ['hello-vairogs', '-', 'vairogs', ],
            ['hello-world', '.', 'hello-world', ],
            ['hello-hello.hello', '.', 'hello', ],
        ];
    }

    public function dataProviderGetNormalizedValue(): array
    {
        return [
            ['0.05', '.', 0.05, ],
            ['0.05', ',', 0, ],
            ['1', 'a', 1, ],
            ['a1', '.', 'a1', ],
            [Vairogs::VAIROGS, '.', Vairogs::VAIROGS, ],
        ];
    }

    public function dataProviderHtmlEntityDecode(): array
    {
        return [
            ["I'll \"walk\" the <b>dog</b> now", ],
        ];
    }

    public function dataProvideReverseUTF8(): array
    {
        return [
            ['vairogs', 'sgoriav', ],
            ['āzis', 'sizā', ],
            ['šķēle', 'elēķš', ],
        ];
    }

    public function dataProviderContainsAny(): array
    {
        return [
            ['vairogs-hello-world', ['one', 'two', ], false, ],
            ['onetwothreevairogs', ['vairogs', 'one', ], true, ],
        ];
    }

    public function dataProviderSanitize(): array
    {
        return [
            ['<p>Pārtraukumi pakalpojumu darbībā</p>', 'Pārtraukumi pakalpojumu darbībā', ],
            ['I walk the <b>dog</b> now', 'I walk the dog now', ],
        ];
    }
}
