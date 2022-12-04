<?php declare(strict_types = 1);

namespace Vairogs\Functions;

use function is_numeric;

final class Number
{
    public function floatToInt32(float $number): int
    {
        return $number & 0xFFFFFFFF;
    }

    public function isInt(mixed $value): bool
    {
        return is_numeric(value: $value) && ctype_digit(text: (string) $value);
    }

    public function isFloat(mixed $value): bool
    {
        return is_numeric(value: $value) && !ctype_digit(text: (string) $value);
    }

    public function greatestCommonDivisor(int $fisrt, int $second): int
    {
        if (0 === $second) {
            return $fisrt;
        }

        return $this->greatestCommonDivisor(fisrt: $second, second: $fisrt % $second);
    }

    public function leastCommonMultiple(int $first, int $second): int
    {
        return (int) ($first * $second / $this->greatestCommonDivisor(fisrt: $first, second: $second));
    }
}
