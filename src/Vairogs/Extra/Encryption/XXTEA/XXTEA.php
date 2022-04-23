<?php declare(strict_types = 1);

namespace Vairogs\Extra\Encryption\XXTEA;

use Vairogs\Utils\Helper\Char;
use Vairogs\Utils\Helper\Number;
use function floor;

final class XXTEA
{
    private const DELTA = 0x9E3779B9;

    public static function encrypt(string $string, string $key): string
    {
        if ('' === $string) {
            return '';
        }

        $vector = Char::str2long(string: $string, wide: true);
        $fixedKey = self::fixKey(key: Char::str2long(string: $key));
        $length = count(value: $vector) - 1;
        $last = $vector[$length];
        $start = floor(num: 6 + 52 / ($length + 1));
        $sum = 0;

        while (0 < $start--) {
            $sum = Number::floatToInt32(number: $sum + self::DELTA);
            $right = $sum >> 2 & 3;

            for ($pointer = 0; $pointer < $length; $pointer++) {
                $first = $vector[$pointer + 1];
                $last = $vector[$pointer] = Number::floatToInt32(number: $vector[$pointer] + self::mx(sum: $sum, first: $first, last: $last, pointer: $pointer, right: $right, key: $fixedKey));
            }

            $first = $vector[0];
            $last = $vector[$length] = Number::floatToInt32(number: $vector[$length] + self::mx(sum: $sum, first: $first, last: $last, pointer: $pointer, right: $right, key: $fixedKey));
        }

        return Char::long2str(array: $vector);
    }

    public static function decrypt(string $string, string $key): string
    {
        if ('' === $string) {
            return '';
        }

        $vector = Char::str2long(string: $string);
        $fixedKey = self::fixKey(key: Char::str2long(string: $key));
        $length = count(value: $vector) - 1;
        $first = $vector[0];
        $sum = Number::floatToInt32(number: floor(num: 6 + 52 / ($length + 1)) * self::DELTA);

        while (0 !== $sum) {
            $shiftRight = $sum >> 2 & 3;

            for ($pointer = $length; $pointer > 0; $pointer--) {
                $last = $vector[$pointer - 1];
                $first = $vector[$pointer] = Number::floatToInt32(number: $vector[$pointer] - self::mx(sum: $sum, first: $first, last: $last, pointer: $pointer, right: $shiftRight, key: $fixedKey));
            }

            $last = $vector[$length];
            $first = $vector[0] = Number::floatToInt32(number: $vector[0] - self::mx(sum: $sum, first: $first, last: $last, pointer: $pointer, right: $shiftRight, key: $fixedKey));
            $sum = Number::floatToInt32(number: $sum - self::DELTA);
        }

        return Char::long2str(array: $vector, wide: true);
    }

    private static function mx(int $sum, int $first, int $last, int $pointer, int $right, array $key): int
    {
        return ((($last >> 5 & 0x07FFFFFF) ^ $first << 2) + (($first >> 3 & 0x1FFFFFFF) ^ $last << 4)) ^ (($sum ^ $first) + ($key[$pointer & 3 ^ $right] ^ $last));
    }

    private static function fixKey(array $key): array
    {
        if (count(value: $key) < 4) {
            for ($i = count(value: $key); $i < 4; $i++) {
                $key[$i] = 0;
            }
        }

        return $key;
    }
}
