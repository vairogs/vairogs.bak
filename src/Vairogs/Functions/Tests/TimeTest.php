<?php declare(strict_types = 1);

namespace Vairogs\Functions\Tests;

use Vairogs\Core\Tests\VairogsTestCase;
use Vairogs\Functions\Time;

class TimeTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\TimeDataProvider::dataProviderFormat
     */
    public function testFormat(int|float $timestamp, string|array $expected, bool $asArray): void
    {
        $this->assertEquals(expected: $expected, actual: (new Time())->format(timestamp: $timestamp, asArray: $asArray));
    }
}
