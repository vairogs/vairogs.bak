<?php declare(strict_types = 1);

namespace Vairogs\Tests\Source\Functions;

use Vairogs\Functions\Json;
use Vairogs\Tests\Assets\VairogsTestCase;

class JsonTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Tests\Assets\Functions\JsonDataProvider::dataProviderJson
     *
     * @noinspection PhpUnhandledExceptionInspection
     */
    public function testJson(mixed $data, int $flags): void
    {
        $this->assertEquals(expected: $data, actual: (new Json())->decode(json: (new Json())->encode(value: $data, flags: $flags), flags: $flags));
    }
}
