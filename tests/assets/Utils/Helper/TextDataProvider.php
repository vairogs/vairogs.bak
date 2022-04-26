<?php declare(strict_types = 1);

namespace Vairogs\Assets\Utils\Helper;

class TextDataProvider
{
    public function dataProviderOneStripSpace(): array
    {
        return [
            ['hello  world vairogs', 'hello world vairogs', 'helloworldvairogs'],
        ];
    }

    public function dataProviderLimit(): array
    {
        return [
            ['sapien nec sagittis aliquam malesuada bibendum arcu vitae elementum curabitur', 32, 4, '...', 'sapien nec sagittis aliquam male...', 'sapien nec sagittis aliquam...', 'sapien nec sagittis aliquam...'],
            ['sapien nec sagittis', 32, 4, '...', 'sapien nec sagittis', 'sapien nec sagittis', 'sapien nec sagittis'],
        ];
    }
}
