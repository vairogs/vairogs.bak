<?php declare(strict_types = 1);

namespace Vairogs\Functions\Tests\DataProvider;

use Vairogs\Functions\Constants\Definition;
use Vairogs\Functions\Constants\Http;

class UriDataProvider
{
    public static function dataProviderArrayFromQueryString(): array
    {
        return [
            ['field1=value1&field2=value2&field3=value3', ['field1' => 'value1', 'field2' => 'value2', 'field3' => 'value3', ], ],
            ['', [], ],
            ['field', ['field' => '', ], ],
        ];
    }

    /** @noinspection HttpUrlsUsage */
    public static function dataProviderGetSchema(): array
    {
        return [
            [Definition::IDENT, Http::SCHEMA_HTTPS, ],
            ['http://ident.me', Http::SCHEMA_HTTP, ],
            ['ident.me', Http::SCHEMA_HTTP, ],
        ];
    }

    public static function dataUrlEncode(): array
    {
        return [
            [Definition::IDENT, ],
            ['https://ident.me:8888', ],
            ['https://ident.me:8888?field1=value1&field2=value2&field3=value3', ],
            ['https://ident.me?field1=value1&field2=value2&field3=value3', ],
        ];
    }

    public static function dataProviderRouteExists(): array
    {
        return [
            ['tests_foo', true, ],
            ['foo', false, ],
        ];
    }

    public static function dataProviderIsUrl(): array
    {
        return [
            [Definition::IDENT, true, ],
            ['vairogs', false, ],
        ];
    }
}
