<?php declare(strict_types = 1);

namespace Vairogs\Tests\Assets\Utils\Helper\Model;

use Vairogs\Core\Attribute\TwigFilter;

class TestAttribute
{
    public static int $a;

    #[TwigFilter]
    public static function test(): void
    {
        self::$a = 1;
    }
}
