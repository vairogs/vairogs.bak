<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils\Handler;

use PHPUnit\Framework\TestCase;
use Vairogs\Utils\Handler\FunctionHandler;

class FunctionHandlerTest extends TestCase
{
    /**
     * @dataProvider \Vairogs\Assets\Utils\Handler\FunctionHandlerDataProvider::dataProvider
     */
    public function test(string $function, ?object $object, mixed $expected, ...$arguments): void
    {
        $this->assertSame(expected: $expected, actual: (new FunctionHandler())->setFunction(functionName: $function, instance: $object)->handle(...$arguments));
    }
}
