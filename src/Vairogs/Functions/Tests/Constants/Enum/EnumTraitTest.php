<?php declare(strict_types = 1);

namespace Vairogs\Functions\Tests\Constants\Enum;

use Vairogs\Core\Tests\VairogsTestCase;

class EnumTraitTest extends VairogsTestCase
{
    public function testEnumTrait(): void
    {
        $this->assertEquals(expected: [], actual: TestEnum::getCases());
        $this->assertEquals(expected: ['one', 'two', ], actual: TestEnumValues::getCases());
    }
}
