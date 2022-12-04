<?php declare(strict_types = 1);

namespace Vairogs\Tests\Source\Functions;

use Vairogs\Functions\Time;
use Vairogs\Tests\Assets\VairogsTestCase;

class TimeTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Tests\Assets\Functions\TimeDataProvider::dataProviderFormat
     */
    public function testFormat(int|float $timestamp, string|array $expected, bool $asArray): void
    {
        $this->assertEquals(expected: $expected, actual: (new Time())->format(timestamp: $timestamp, asArray: $asArray));
    }
}
