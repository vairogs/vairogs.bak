<?php declare(strict_types = 1);

namespace Vairogs\Tests\Assets\Utils\Helper;

use Vairogs\Extra\Constants\Enum\CamelCase;

class CharDataProvider
{
    public static function dataProviderSanitizeFloat(): array
    {
        return [
            ['0.56', 0.56, ],
            ['1', 1.00, ],
            ['a', 0.00, ],
            ['a1', 1.00, ],
        ];
    }

    public static function dataProviderToSnakeCase(): array
    {
        return [
            ['VairogsHelper', false, 'vairogshelper', ],
            ['VairogsHelper', true, 'vairogs_helper', ],
        ];
    }

    public static function dataProviderFromCamelCase(): array
    {
        return [
            ['VairogsHelper', '_', 'vairogs_helper', ],
            ['VairogsHelper', '', 'vairogshelper', ],
        ];
    }

    public static function dataProviderToCamelCase(): array
    {
        return [
            ['vairogs_helper', CamelCase::LCFIRST, 'vairogsHelper', ],
            ['vairogshelper', CamelCase::UCFIRST, 'Vairogshelper', ],
            ['hello_world', CamelCase::UCFIRST, 'HelloWorld', ],
        ];
    }
}
