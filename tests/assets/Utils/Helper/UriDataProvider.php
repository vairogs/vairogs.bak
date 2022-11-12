<?php declare(strict_types = 1);

namespace Vairogs\Tests\Assets\Utils\Helper;

use Vairogs\Extra\Constants\Definition;
use Vairogs\Extra\Constants\Http;

class UriDataProvider
{
    public function dataProviderArrayFromQueryString(): array
    {
        return [
            ['field1=value1&field2=value2&field3=value3', ['field1' => 'value1', 'field2' => 'value2', 'field3' => 'value3', ], ],
            ['', [], ],
            ['field', ['field' => '', ], ],
        ];
    }

    /** @noinspection HttpUrlsUsage */
    public function dataProviderGetSchema(): array
    {
        return [
            [Definition::IDENT, Http::SCHEMA_HTTPS, ],
            ['http://ident.me', Http::SCHEMA_HTTP, ],
            ['ident.me', Http::SCHEMA_HTTP, ],
        ];
    }

    public function dataUrlEncode(): array
    {
        return [
            [Definition::IDENT, ],
            ['https://ident.me:8888', ],
            ['https://ident.me:8888?field1=value1&field2=value2&field3=value3', ],
            ['https://ident.me?field1=value1&field2=value2&field3=value3', ],
        ];
    }

    public function dataProviderRouteExists(): array
    {
        return [
            ['tests_foo', true, ],
            ['foo', false, ],
        ];
    }

    public function dataProviderIsUrl(): array
    {
        return [
            [Definition::IDENT, true, ],
            ['vairogs', false, ],
        ];
    }
}
