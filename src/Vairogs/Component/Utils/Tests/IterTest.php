<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils;

use PHPUnit\Framework\TestCase;
use Vairogs\Utils\Iter;

class IterTest extends TestCase
{
    public function testIsEmpty(): void
    {
        $this->assertTrue(Iter::isEmpty([[[[[]]]]]));
        $this->assertTrue(Iter::isEmpty([[[[[null], null]]]]));
        $this->assertFalse(Iter::isEmpty([[[], 1], 4]));
    }

    public function testUnique2(): void
    {
        $valid = $validTest = ['apple', 'orange'];
        $remove = $removeTest = ['apple', 'orange', 'apple'];

        Iter::unique2($validTest);
        Iter::unique2($removeTest);

        $this->assertSame($valid, $validTest);
        $this->assertNotSame($remove, $removeTest);
    }

    public function testIsMultidimensional(): void
    {
        $this->assertFalse(Iter::isMultiDimensional([1 => 1, 2 => 3]));
        $this->assertTrue(Iter::isMultiDimensional([1 => [1 => 1], 2 => 3]));
    }

    public function testIsAnyKeyNull(): void
    {
        $this->assertFalse(Iter::isAnyKeyNull([1, 2, 3, 4]));
        $this->assertTrue(Iter::isAnyKeyNull([1, 2, null, 4]));
    }

    public function testMakeOneDimension(): void
    {
        $full = [
            'one.two.three' => 'four',
            'one.two' => [
                'three' => 'four',
            ],
            'one' => [
                'two' => [
                    'three' => 'four',
                ],
            ],
        ];
        $simple = [
            'one.two.three' => 'four',
            'one.two' => [
                'three' => 'four',
            ],
        ];
        $number = [
            'one.two' => [
                'three',
            ],
        ];
        $this->assertSame($full, Iter::makeOneDimension(['one' => ['two' => ['three' => 'four']]]));
        $this->assertSame($simple, Iter::makeOneDimension(['one' => ['two' => ['three' => 'four']]], '', '.', true));
        $this->assertSame($number, Iter::makeOneDimension(['one' => ['two' => ['three']]], '', '.', true));
    }

    public function testIsAssociative(): void
    {
        $this->assertTrue(Iter::isAssociative(['a' => 1, 'b' => 3]));
        $this->assertFalse(Iter::isAssociative([1, 2, 3]));
        $this->assertTrue(Iter::isAssociative(['a' => 1, 2]));
    }
}
