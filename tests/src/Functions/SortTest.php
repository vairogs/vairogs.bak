<?php declare(strict_types = 1);

namespace Vairogs\Tests\Source\Functions;

use Vairogs\Functions\Sort;
use Vairogs\Tests\Assets\VairogsTestCase;

class SortTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Tests\Assets\Functions\SortDataProvider::dataProviderSwap
     */
    public function testSwap(int $a, int $b): void
    {
        $value = [$b, $a, ];
        (new Sort())->swap(foo: $a, bar: $b);
        $this->assertEquals(expected: $value, actual: [$a, $b]);
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Functions\SortDataProvider::dataProviderSwap
     */
    public function testSwapArray(int $a, int $b): void
    {
        $expected = [$a, $b, ];
        $actual = [$b, $a, ];
        (new Sort())->swapArray(array: $actual, foo: 0, bar: 1);
        $this->assertEquals(expected: $expected, actual: $actual);
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Functions\SortDataProvider::dataProviderBubbleSort
     */
    public function testBubbleSort(array $unsorted, array $sorted): void
    {
        (new Sort())->bubbleSort(array: $unsorted);
        $this->assertEquals(expected: $sorted, actual: $unsorted);
    }

    /**
     * @dataProvider \Vairogs\Tests\Assets\Functions\SortDataProvider::dataProviderBubbleSort
     */
    public function testMergeSort(array $unsorted, array $sorted): void
    {
        $this->assertEquals(expected: $sorted, actual: (new Sort())->mergeSort(array: $unsorted));
    }
}
