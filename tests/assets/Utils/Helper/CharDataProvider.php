<?php declare(strict_types = 1);

namespace Vairogs\Assets\Utils\Helper;

class CharDataProvider
{
    public function dataProviderSanitizeFloat(): array
    {
        return [
            ['0.56', 0.56],
            ['1', 1.00],
            ['a', 0.00],
            ['a1', 1.00],
        ];
    }

    public function dataProviderToSnakeCase(): array
    {
        return [
            ['VairogsHelper', false, 'vairogshelper'],
            ['VairogsHelper', true, 'vairogs_helper'],
        ];
    }

    public function dataProviderFromCamelCase(): array
    {
        return [
            ['VairogsHelper', '_', 'vairogs_helper'],
            ['VairogsHelper', '', 'vairogshelper'],
        ];
    }

    public function dataProviderBase62(): array
    {
        return [
            [12578952],
            [0],
            [3],
            [777],
        ];
    }
}
