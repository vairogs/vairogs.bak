<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils\Helper;

use Vairogs\Assets\VairogsTestCase;
use Vairogs\Utils\Helper\Json;

class JsonTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\JsonDataProvider::dataProviderJson
     * @noinspection PhpUnhandledExceptionInspection
     */
    public function testJson(mixed $data, int $flags): void
    {
        $this->assertEquals(expected: $data, actual: (new Json())->decode((new Json())->encode($data, $flags), $flags));
    }
}
