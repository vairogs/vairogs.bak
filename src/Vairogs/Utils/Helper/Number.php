<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use Vairogs\Core\Attribute\CoreFilter;
use Vairogs\Core\Attribute\CoreFunction;

use function ctype_digit;
use function is_numeric;

final class Number
{
    #[CoreFunction]
    #[CoreFilter]
    public function floatToInt32(float $number): int
    {
        return $number & 0xFFFFFFFF;
    }

    #[CoreFunction]
    #[CoreFilter]
    public function isInt(mixed $value): bool
    {
        return is_numeric(value: $value) && ctype_digit(text: (string) $value);
    }

    #[CoreFunction]
    #[CoreFilter]
    public function isFloat(mixed $value): bool
    {
        return is_numeric(value: $value) && !ctype_digit(text: (string) $value);
    }

    #[CoreFunction]
    #[CoreFilter]
    public function greatestCommonDivisor(int $fisrt, int $second): int
    {
        if (0 === $second) {
            return $fisrt;
        }

        return $this->greatestCommonDivisor(fisrt: $second, second: $fisrt % $second);
    }

    #[CoreFunction]
    #[CoreFilter]
    public function leastCommonMultiple(int $first, int $second): int
    {
        return (int) ($first * $second / $this->greatestCommonDivisor(fisrt: $first, second: $second));
    }
}
