<?php declare(strict_types = 1);

namespace Vairogs\Tests\Source\Utils\Helper;

use Vairogs\Tests\Assets\VairogsTestCase;
use Vairogs\Utils\Helper\Json;

class JsonTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\JsonDataProvider::dataProviderJson
     * @noinspection PhpUnhandledExceptionInspection
     */
    public function testJson(mixed $data, int $flags): void
    {
        $this->assertEquals(expected: $data, actual: (new Json())->decode(json: (new Json())->encode(value: $data, flags: $flags), flags: $flags));
    }
}
