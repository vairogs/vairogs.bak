<?php declare(strict_types = 1);

namespace Vairogs\Functions\Tests\DataProvider;

class TextDataProvider
{
    public static function dataProviderOneStripSpace(): array
    {
        return [
            ['hello  world vairogs', 'hello world vairogs', 'helloworldvairogs', ],
        ];
    }

    public static function dataProviderLimit(): array
    {
        return [
            ['sapien nec sagittis aliquam malesuada bibendum arcu vitae elementum curabitur', 32, 4, '...', 'sapien nec sagittis aliquam male...', 'sapien nec sagittis aliquam...', 'sapien nec sagittis aliquam...', ],
            ['sapien nec sagittis', 32, 4, '...', 'sapien nec sagittis', 'sapien nec sagittis', 'sapien nec sagittis', ],
        ];
    }

    public static function dataProviderGetLastPart(): array
    {
        return [
            ['hello-vairogs', '-', 'vairogs', ],
            ['hello-world', '.', 'hello-world', ],
            ['hello-hello.hello', '.', 'hello', ],
        ];
    }

    public static function dataProviderGetNormalizedValue(): array
    {
        return [
            ['0.05', '.', 0.05, ],
            ['0.05', ',', 0, ],
            ['1', 'a', 1, ],
            ['a1', '.', 'a1', ],
            [__FUNCTION__, '.', __FUNCTION__, ],
        ];
    }

    public static function dataProviderHtmlEntityDecode(): array
    {
        return [
            ["I'll \"walk\" the <b>dog</b> now", ],
        ];
    }

    public static function dataProvideReverseUTF8(): array
    {
        return [
            ['vairogs', 'sgoriav', ],
            ['āzis', 'sizā', ],
            ['šķēle', 'elēķš', ],
        ];
    }

    public static function dataProviderContainsAny(): array
    {
        return [
            ['vairogs-hello-world', ['one', 'two', ], false, ],
            ['onetwothreevairogs', ['vairogs', 'one', ], true, ],
        ];
    }

    public static function dataProviderSanitize(): array
    {
        return [
            ['<p>Pārtraukumi pakalpojumu darbībā</p>', 'Pārtraukumi pakalpojumu darbībā', ],
            ['I walk the <b>dog</b> now', 'I walk the dog now', ],
        ];
    }

    public static function dataProviderLongestSubstrLength(): array
    {
        return [
            ['abcabcabc', 3, ],
            ['abcdabcdeabcdabcdef', 6, ],
            ['dvdf', 3, ],
        ];
    }
}
