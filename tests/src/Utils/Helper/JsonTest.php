<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils\Helper;

use PHPUnit\Framework\TestCase;
use Vairogs\Utils\Helper\Json;

class JsonTest extends TestCase
{
    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\JsonDataProvider::dataProviderJson
     */
    public function testJson(mixed $data, int $flags): void
    {
        $this->assertEquals(expected: $data, actual: Json::decode(Json::encode($data, $flags), $flags));
    }
}
