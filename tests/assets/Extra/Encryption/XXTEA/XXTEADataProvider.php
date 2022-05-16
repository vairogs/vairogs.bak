<?php declare(strict_types = 1);

namespace Vairogs\Assets\Extra\Encryption\XXTEA;

class XXTEADataProvider
{
    public function dataProvider(): array
    {
        return [
            ['Vairogs! 盾牌！ Štít', 'testVAIROGS789', ],
            ['', '123', ],
            ['Vairogs!', '123', ],
        ];
    }
}
