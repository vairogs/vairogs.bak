<?php declare(strict_types = 1);

namespace Vairogs\Tests\Assets\Utils\Helper;

use Vairogs\Utils\Helper\Char;

class CharDataProvider
{
    public function dataProviderSanitizeFloat(): array
    {
        return [
            ['0.56', 0.56, ],
            ['1', 1.00, ],
            ['a', 0.00, ],
            ['a1', 1.00, ],
        ];
    }

    public function dataProviderToSnakeCase(): array
    {
        return [
            ['VairogsHelper', false, 'vairogshelper', ],
            ['VairogsHelper', true, 'vairogs_helper', ],
        ];
    }

    public function dataProviderFromCamelCase(): array
    {
        return [
            ['VairogsHelper', '_', 'vairogs_helper', ],
            ['VairogsHelper', '', 'vairogshelper', ],
        ];
    }

    public function dataProviderToCamelCase(): array
    {
        return [
            ['vairogs_helper', Char::LCFIRST, 'vairogsHelper', ],
            ['vairogshelper', Char::UCFIRST, 'Vairogshelper', ],
            ['hello_world', Char::UCFIRST, 'HelloWorld', ],
        ];
    }
}
