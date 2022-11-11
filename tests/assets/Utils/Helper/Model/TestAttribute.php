<?php declare(strict_types = 1);

namespace Vairogs\Tests\Assets\Utils\Helper\Model;

use Vairogs\Core\Attribute\CoreFilter;

class TestAttribute
{
    public static int $a;

    #[CoreFilter]
    public static function test(): void
    {
        self::$a = 1;
    }
}
