<?php declare(strict_types = 1);

namespace Vairogs\Tests\Assets\Utils\Encryption;

class XXTEADataProvider
{
    public static function dataProvider(): array
    {
        return [
            ['Vairogs! 盾牌！ Štít', 'testVAIROGS789', ],
            ['', '123', ],
            ['Vairogs!', '123', ],
        ];
    }
}
