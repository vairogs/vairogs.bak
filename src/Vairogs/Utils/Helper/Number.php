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
    public static function greatestCommonDivisor(int $fisrt, int $second): int
    {
        if (0 === $second) {
            return $fisrt;
        }

        return self::greatestCommonDivisor(fisrt: $second, second: $fisrt % $second);
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function leastCommonMultiple(int $first, int $second): int
    {
        return $first * $second / self::greatestCommonDivisor(fisrt: $first, second: $second);
    }
}
