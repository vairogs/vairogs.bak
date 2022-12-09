<?php declare(strict_types = 1);

namespace Vairogs\Functions\Tests;

use Vairogs\Core\Tests\VairogsTestCase;
use Vairogs\Functions\Abstraction;
use Vairogs\Functions\Text;

class AbstractionTest extends VairogsTestCase
{
    public function testShuffle(): void
    {
        $supported = new class(80200) extends Abstraction {};
        $notSupported = new class(80400) extends Abstraction {};

        $this->assertTrue((new Text())->contains($supported->shuffle('abcdefgh'), 'e'));
        $this->assertTrue((new Text())->contains($notSupported->shuffle('abcdefgh'), 'b'));
    }

    public function testPick(): void
    {
        $supported = new class(80200) extends Abstraction {};
        $notSupported = new class(80400) extends Abstraction {};

        $this->assertContains(needle: $supported->pick(array: ['a' => 'a', 'b' => 'b', 'c' => 'c', 'd' => 'd', ]), haystack: ['a', 'b', 'c', 'd', ]);
        $this->assertContains(needle: $notSupported->pick(array: ['a' => 'a', 'b' => 'b', 'c' => 'c', 'd' => 'd', ]), haystack: ['a', 'b', 'c', 'd', ]);
    }
}
