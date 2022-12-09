<?php declare(strict_types = 1);

namespace Vairogs\Functions\Tests;

use Vairogs\Core\Tests\VairogsTestCase;
use Vairogs\Functions\Json;

class JsonTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\JsonDataProvider::dataProviderJson
     *
     * @noinspection PhpUnhandledExceptionInspection
     */
    public function testJson(mixed $data, int $flags): void
    {
        $this->assertEquals(expected: $data, actual: (new Json())->decode(json: (new Json())->encode(value: $data, flags: $flags), flags: $flags));
    }
}
