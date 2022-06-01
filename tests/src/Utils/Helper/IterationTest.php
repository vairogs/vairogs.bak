<?php declare(strict_types = 1);

namespace Vairogs\Tests\Source\Utils\Helper;

use Exception;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Vairogs\Tests\Assets\VairogsTestCase;
use Vairogs\Utils\Helper\Iteration;
use Vairogs\Utils\Helper\Php;

class IterationTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\IterationDataProvider::dataProviderIsEmpty
     */
    public function testIsEmpty(mixed $value, bool $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Iteration())->isEmpty(variable: $value));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\IterationDataProvider::dataProviderMakeMultiDimensional
     */
    public function testMakeMultiDimensional(array $input, array $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Iteration())->makeMultiDimensional(array: $input));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\IterationDataProvider::dataProviderUniqueMap
     */
    public function testUniqueMap(array $input, array $expected): void
    {
        (new Iteration())->uniqueMap(array: $input);
        $this->assertEquals(expected: $expected, actual: $input);
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\IterationDataProvider::dataProviderUnique
     */
    public function testUnique(array $input, array $expected, bool $keep): void
    {
        $this->assertEquals(expected: $expected, actual: (new Iteration())->unique(input: $input, keepKeys: $keep));
    }

    public function testRemoveFromArray(): void
    {
        $clean = $input = ['one', 'two', 'three', 'four', ];
        (new Iteration())->removeFromArray(input: $clean, value: 'two');
        $this->assertNotEquals(expected: $input, actual: $clean);
    }

    public function testFilterKeyEndsWith(): void
    {
        $this->assertEquals(expected: Request::METHOD_POST, actual: (new Iteration())->filterKeyEndsWith(input: (new Php())->getClassConstants(class: Request::class), endsWith: Request::METHOD_POST)['METHOD_POST'] ?? null);
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\IterationDataProvider::dataProviderArrayIntersectKeyRecursive
     */
    public function testArrayIntersectKeyRecursive(array $input, array $second, array $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Iteration())->arrayIntersectKeyRecursive(first: $input, second: $second));
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Utils\Helper\IterationDataProvider::dataProviderArrayFlipRecursive
     */
    public function testArrayFlipRecursive(array $input, array $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Iteration())->arrayFlipRecursive(input: $input));
    }

    public function testArrayFlipRecursiveException(): void
    {
        try {
            (new Iteration())->arrayFlipRecursive(input: ['1' => false]);
        } catch (Exception $exception) {
            $this->assertEquals(expected: InvalidArgumentException::class, actual: $exception::class);
        }
    }
}
