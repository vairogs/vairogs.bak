<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use Vairogs\Utils\Twig\Attribute;
use function ctype_digit;
use function is_numeric;

final class Number
{
    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function floatToInt32(float $number): int
    {
        return $number & 0xFFFFFFFF;
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function isInt(mixed $value): bool
    {
        return is_numeric(value: $value) && ctype_digit(text: (string) $value);
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function isFloat(mixed $value): bool
    {
        return is_numeric(value: $value) && !ctype_digit(text: (string) $value);
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function greatestCommonDivisor(int $x, int $y): int
    {
        if (0 === $y) {
            return $x;
        }

        return self::greatestCommonDivisor(x: $y, y: $x % $y);
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function leastCommonMultiple(int $x, int $y): int
    {
        return $x * $y / self::greatestCommonDivisor(x: $x, y: $y);
    }
}
