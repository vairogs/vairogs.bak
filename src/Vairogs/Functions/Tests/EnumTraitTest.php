<?php declare(strict_types = 1);

namespace Vairogs\Functions\Tests;

use Vairogs\Core\Tests\VairogsTestCase;
use Vairogs\Functions\Tests\Assets\Constants\Enum\TestEnum;
use Vairogs\Functions\Tests\Assets\Constants\Enum\TestEnumValues;

class EnumTraitTest extends VairogsTestCase
{
    public function testEnumTrait(): void
    {
        $this->assertEquals(expected: [], actual: TestEnum::getCases());
        $this->assertEquals(expected: ['one', 'two', ], actual: TestEnumValues::getCases());
    }
}
