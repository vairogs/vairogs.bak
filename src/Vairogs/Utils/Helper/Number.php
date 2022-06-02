<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use Vairogs\Twig\Attribute\TwigFilter;
use Vairogs\Twig\Attribute\TwigFunction;
use function ctype_digit;
use function is_numeric;

final class Number
{
    #[TwigFunction]
    #[TwigFilter]
    public function floatToInt32(float $number): int
    {
        return $number & 0xFFFFFFFF;
    }

    #[TwigFunction]
    #[TwigFilter]
    public function isInt(mixed $value): bool
    {
        return is_numeric(value: $value) && ctype_digit(text: (string) $value);
    }

    #[TwigFunction]
    #[TwigFilter]
    public function isFloat(mixed $value): bool
    {
        return is_numeric(value: $value) && !ctype_digit(text: (string) $value);
    }

    #[TwigFunction]
    #[TwigFilter]
    public function greatestCommonDivisor(int $fisrt, int $second): int
    {
        if (0 === $second) {
            return $fisrt;
        }

        return $this->greatestCommonDivisor(fisrt: $second, second: $fisrt % $second);
    }

    #[TwigFunction]
    #[TwigFilter]
    public function leastCommonMultiple(int $first, int $second): int
    {
        return (int) ($first * $second / $this->greatestCommonDivisor(fisrt: $first, second: $second));
    }
}
