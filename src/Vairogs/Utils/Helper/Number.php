<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use Vairogs\Utils\Twig\Attribute;

final class Number
{
    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function floatToInt32(float $number): int
    {
        return $number & 0xFFFFFFFF;
    }
}
