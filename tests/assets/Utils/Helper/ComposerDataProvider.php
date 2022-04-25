<?php declare(strict_types = 1);

namespace Vairogs\Assets\Utils\Helper;

class ComposerDataProvider
{
    public function dataProviderIsInstalled(): array
    {
        return [
            ['json', true, false],
            ['ext-xdebug', true, true],
            ['ext-oci8', false, true],
            ['phpunit/phpunit', true, true],
            ['phpunit/phpunit', false, false],
        ];
    }
}
