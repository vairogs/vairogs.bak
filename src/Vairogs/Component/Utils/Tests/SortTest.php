<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils;

use PHPUnit\Framework\TestCase;
use Vairogs\Component\Utils\Sort;

class SortTest extends TestCase
{
    public function testSwap(): void
    {
        $a = $c = 1;
        $b = $d = 2;
        Sort::swap($a, $b);
        $this->assertNotSame([$a, $b], [$c, $d]);
        $this->assertSame([$a, $b], [$d, $c]);
    }

    public function testBubbleSort(): void
    {
        $array = $sorted = [1, 2, 5, 3, 7, 4, 7, 4, 9];
        $result = [1, 2, 3, 4, 4, 5, 7, 7, 9];
        Sort::bubbleSort($sorted);
        $this->assertSame($result, $sorted);
        $this->assertNotSame($array, $sorted);
    }

    public function testMergeSort(): void
    {
        $array = [1, 2, 5, 3, 7, 4, 7, 4, 9];
        $result = [1, 2, 3, 4, 4, 5, 7, 7, 9];
        $sorted = Sort::mergeSort($array);
        $this->assertSame($result, $sorted);
        $this->assertNotSame($array, $sorted);
    }

    public function testSwapArray(): void
    {
        $array = $swapped = ['apple' => 'green', 'lemon' => 'yellow'];
        $result = ['apple' => 'yellow', 'lemon' => 'green'];
        Sort::swapArray($swapped, 'apple', 'lemon');
        $this->assertSame($result, $swapped);
        $this->assertNotSame($array, $swapped);
    }
}
