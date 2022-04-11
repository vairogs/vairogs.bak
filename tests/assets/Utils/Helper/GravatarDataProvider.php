<?php declare(strict_types = 1);

namespace Vairogs\Assets\Utils\Helper;

use Vairogs\Utils\Helper\Gravatar;

class GravatarDataProvider
{
    public function dataProviderGetGravatarUrl(): array
    {
        return [
            [Gravatar::DEFAULT_EMAIL, false, 32, Gravatar::ICON_IDENTICON, 'http://www.gravatar.com/avatar/817bf5e10e1afad8a2609f0034d01620/?s=32&d=identicon'],
            ['', false, 32, Gravatar::ICON_IDENTICON, 'http://www.gravatar.com/avatar/817bf5e10e1afad8a2609f0034d01620/?s=32&d=identicon'],
            [Gravatar::DEFAULT_EMAIL, true, 64, Gravatar::ICON_IDENTICON, 'https://secure.gravatar.com/avatar/817bf5e10e1afad8a2609f0034d01620/?s=64&d=identicon'],
            [Gravatar::DEFAULT_EMAIL, true, 256, Gravatar::ICON_RETRO, 'https://secure.gravatar.com/avatar/817bf5e10e1afad8a2609f0034d01620/?s=256&d=retro'],
        ];
    }
}
