<?php declare(strict_types = 1);

namespace Vairogs\Tests\Source\Functions\Handler;

use Vairogs\Functions\Handler\FunctionHandler;
use Vairogs\Tests\Assets\VairogsTestCase;

class FunctionHandlerTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Tests\Assets\Functions\Handler\FunctionHandlerDataProvider::dataProvider
     */
    public function test(string $function, ?object $object, mixed $expected, ...$arguments): void
    {
        $this->assertEquals(expected: $expected, actual: (new FunctionHandler(function: $function, instance: $object))->handle(...$arguments));
    }
}
