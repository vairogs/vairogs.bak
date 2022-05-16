<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils\Helper;

use Doctrine\Common\Collections\Collection;
use InvalidArgumentException;
use Vairogs\Assets\Utils\Doctrine\Traits\Entity;
use Vairogs\Assets\VairogsTestCase;
use Vairogs\Extra\Constants\Enum\Criteria;
use Vairogs\Utils\Helper\Sort;

class SortTest extends VairogsTestCase
{
    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\SortDataProvider::dataProviderSwap
     */
    public function testSwap(int $a, int $b): void
    {
        $value = [$b, $a, ];
        (new Sort())->swap(foo: $a, bar: $b);
        $this->assertEquals(expected: $value, actual: [$a, $b]);
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\SortDataProvider::dataProviderSwap
     */
    public function testSwapArray(int $a, int $b): void
    {
        $expected = [$a, $b, ];
        $actual = [$b, $a, ];
        (new Sort())->swapArray(array: $actual, foo: 0, bar: 1);
        $this->assertEquals(expected: $expected, actual: $actual);
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\SortDataProvider::dataProviderBubbleSort
     */
    public function testBubbleSort(array $unsorted, array $sorted): void
    {
        (new Sort())->bubbleSort(array: $unsorted);
        $this->assertEquals(expected: $sorted, actual: $unsorted);
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\SortDataProvider::dataProviderBubbleSort
     */
    public function testMergeSort(array $unsorted, array $sorted): void
    {
        $this->assertEquals(expected: $sorted, actual: (new Sort())->mergeSort(array: $unsorted));
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\SortDataProvider::dataProviderSort
     */
    public function testSort(array|object $data, string $parameter, Criteria $order, array $expected): void
    {
        $this->assertEquals(expected: $expected, actual: (new Sort())->sort(data: $data, parameter: $parameter, order: $order));
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\SortDataProvider::dataProviderSortException
     */
    public function testSortException(iterable|Collection $data, string $parameter, Criteria $order): void
    {
        $this->expectException(exception: InvalidArgumentException::class);
        (new Sort())->sort(data: $data, parameter: $parameter, order: $order);
    }

    public function testIsSortable(): void
    {
        $entity = (new Entity())
            ->setId(id: 1);

        $this->assertFalse(condition: (new Sort())->isSortable(item: 1, field: 'value'));
        $this->assertFalse(condition: (new Sort())->isSortable(item: $entity, field: 'test'));
        $this->assertTrue(condition: (new Sort())->isSortable(item: $entity, field: 'id'));
    }
}
