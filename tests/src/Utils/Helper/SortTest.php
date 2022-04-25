<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils\Helper;

use Doctrine\Common\Collections\Collection;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Vairogs\Assets\Utils\Doctrine\Traits\Entity;
use Vairogs\Extra\Constants\Enum\Criteria;
use Vairogs\Utils\Helper\Sort;

class SortTest extends TestCase
{
    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\SortDataProvider::dataProviderSwap
     */
    public function testSwap(int $a, int $b): void
    {
        $value = [$b, $a];
        Sort::swap(foo: $a, bar: $b);
        $this->assertEquals(expected: $value, actual: [$a, $b]);
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\SortDataProvider::dataProviderSwap
     */
    public function testSwapArray(int $a, int $b): void
    {
        $expected = [$a, $b];
        $actual = [$b, $a];
        Sort::swapArray(array: $actual, foo: 0, bar: 1);
        $this->assertEquals(expected: $expected, actual: $actual);
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\SortDataProvider::dataProviderBubbleSort
     */
    public function testBubbleSort(array $unsorted, array $sorted): void
    {
        Sort::bubbleSort(array: $unsorted);
        $this->assertEquals(expected: $sorted, actual: $unsorted);
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\SortDataProvider::dataProviderBubbleSort
     */
    public function testMergeSort(array $unsorted, array $sorted): void
    {
        $this->assertEquals(expected: $sorted, actual: Sort::mergeSort(array: $unsorted));
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\SortDataProvider::dataProviderSort
     */
    public function testSort(iterable|Collection $data, string $parameter, Criteria $order, array $expected): void
    {
        $this->assertEquals(expected: $expected, actual: Sort::sort(data: $data, parameter: $parameter, order: $order));
    }

    /**
     * @dataProvider \Vairogs\Assets\Utils\Helper\SortDataProvider::dataProviderSortException
     */
    public function testSortException(iterable|Collection $data, string $parameter, Criteria $order, array $expected): void
    {
        $this->expectException(InvalidArgumentException::class);
        Sort::sort(data: $data, parameter: $parameter, order: $order);
    }

    public function testIsSortable(): void
    {
        $entity = (new Entity())
            ->setId(id: 1);

        $this->assertFalse(condition: Sort::isSortable(item: 1, field: 'value'));
        $this->assertFalse(condition: Sort::isSortable(item: $entity, field: 'test'));
        $this->assertTrue(condition: Sort::isSortable(item: $entity, field: 'id'));
    }
}
