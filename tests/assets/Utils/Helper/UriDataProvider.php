<?php declare(strict_types = 1);

namespace Vairogs\Assets\Utils\Helper;

use Vairogs\Extra\Constants\Http;

class UriDataProvider
{
    public function dataProviderArrayFromQueryString(): array
    {
        return [
            ['field1=value1&field2=value2&field3=value3', ['field1' => 'value1', 'field2' => 'value2', 'field3' => 'value3']],
            ['', []],
            ['field', ['field' => '']],
        ];
    }

    public function dataProviderGetSchema(): array
    {
        return [
            ['https://ident.me', Http::SCHEMA_HTTPS],
            ['http://ident.me', Http::SCHEMA_HTTP],
            ['ident.me', Http::SCHEMA_HTTP],
        ];
    }

    public function dataUrlEncode(): array
    {
        return [
            ['https://ident.me'],
            ['https://ident.me:8888'],
            ['https://ident.me:8888?field1=value1&field2=value2&field3=value3'],
            ['https://ident.me?field1=value1&field2=value2&field3=value3'],
        ];
    }
}