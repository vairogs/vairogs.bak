<?php declare(strict_types = 1);

namespace Vairogs\Tests\Source\Utils\Helper;

use Vairogs\Tests\Assets\VairogsTestCase;
use Vairogs\Utils\Helper\Time;

class TimeTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\TimeDataProvider::dataProviderFormat
     */
    public function testFormat(int|float $timestamp, string|array $expected, bool $asArray): void
    {
        $this->assertEquals(expected: $expected, actual: (new Time())->format(timestamp: $timestamp, asArray: $asArray));
    }
}
