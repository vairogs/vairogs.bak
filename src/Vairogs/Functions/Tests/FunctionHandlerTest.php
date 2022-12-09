<?php declare(strict_types = 1);

namespace Vairogs\Functions\Tests;

use Vairogs\Core\Tests\VairogsTestCase;
use Vairogs\Functions\Handler\FunctionHandler;

class FunctionHandlerTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Functions\Tests\DataProvider\FunctionHandlerDataProvider::dataProvider
     */
    public function test(string $function, ?object $object, mixed $expected, ...$arguments): void
    {
        $this->assertEquals(expected: $expected, actual: (new FunctionHandler(function: $function, instance: $object))->handle(...$arguments));
    }
}
