<?php declare(strict_types = 1);

namespace Vairogs\Tests\Extra\Constants\Enum;

use Vairogs\Assets\VairogsTestCase;
use Vairogs\Auth\OpenIDConnect\Utils\Constants\Enum\Redirect;

class EnumTest extends VairogsTestCase
{
    public function testEnumTrait(): void
    {
        $this->assertEquals(expected: [], actual: TestEnum::getCases());
        $this->assertEquals(expected: ['uri', 'route', ], actual: Redirect::getCases());
    }
}
