<?php declare(strict_types = 1);

namespace Vairogs\Tests\Extra\Constants\Enum;

use Vairogs\Auth\OpenIDConnect\Utils\Constants\Enum\Redirect;
use Vairogs\Tests\Assets\VairogsTestCase;

class EnumTest extends VairogsTestCase
{
    public function testEnumTrait(): void
    {
        $this->assertEquals(expected: [], actual: TestEnum::getCases());
        $this->assertEquals(expected: ['route', 'uri', ], actual: Redirect::getCases());
    }
}
