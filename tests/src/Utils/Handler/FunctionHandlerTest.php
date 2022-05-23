<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils\Handler;

use Vairogs\Tests\Assets\VairogsTestCase;
use Vairogs\Utils\Handler\FunctionHandler;

class FunctionHandlerTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Handler\FunctionHandlerDataProvider::dataProvider
     */
    public function test(string $function, ?object $object, mixed $expected, ...$arguments): void
    {
        $this->assertEquals(expected: $expected, actual: (new FunctionHandler())->setFunction(functionName: $function, instance: $object)->handle(...$arguments));
    }
}
