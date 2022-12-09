<?php declare(strict_types = 1);

namespace Vairogs\Functions\Tests\DataProvider;

class ComposerDataProvider
{
    public static function dataProviderIsInstalled(): array
    {
        return [
            ['json', true, false, ],
            ['xdebug', true, true, ],
            ['oci8', false, true, ],
            ['phpunit/phpunit', true, true, ],
            ['phpunit/phpunit', false, false, ],
        ];
    }
}
