<?php declare(strict_types = 1);

namespace Vairogs\Assets\Utils\Helper;

class ComposerDataProvider
{
    public function dataProviderIsInstalled(): array
    {
        return [
            ['redis', true, false],
            ['ext-xdebug', true, true],
            ['ext-mysql', false, true],
            ['phpunit/phpunit', true, true],
            ['phpunit/phpunit', false, false],
        ];
    }
}
