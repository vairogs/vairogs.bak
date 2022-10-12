<?php declare(strict_types = 1);

namespace Vairogs\Extra\Encryption\XXTEA;

use Vairogs\Utils\Helper\Convert;
use Vairogs\Utils\Helper\Number;

use function count;
use function floor;

final class XXTEA
{
    private const DELTA = 0x9E3779B9;

    public function encrypt(string $string, string $key): string
    {
        if ('' === $string) {
            return '';
        }

        $vector = (new Convert())->str2long(string: $string, wide: true);
        $fixedKey = $this->fixKey(key: (new Convert())->str2long(string: $key));
        $length = count(value: $vector) - 1;
        $last = $vector[$length];
        $start = floor(num: 6 + 52 / ($length + 1));
        $sum = 0;

        while (0 < $start--) {
            $sum = (new Number())->floatToInt32(number: $sum + self::DELTA);
            $right = $sum >> 2 & 3;

            for ($pointer = 0; $pointer < $length; $pointer++) {
                $first = $vector[$pointer + 1];
                $last = $vector[$pointer] = (new Number())->floatToInt32(number: $vector[$pointer] + $this->mx(sum: $sum, first: $first, last: $last, pointer: $pointer, right: $right, key: $fixedKey));
            }

            $first = $vector[0];
            $last = $vector[$length] = (new Number())->floatToInt32(number: $vector[$length] + $this->mx(sum: $sum, first: $first, last: $last, pointer: $pointer, right: $right, key: $fixedKey));
        }

        return (new Convert())->long2str(array: $vector);
    }

    public function decrypt(string $string, string $key): string
    {
        if ('' === $string) {
            return '';
        }

        $vector = (new Convert())->str2long(string: $string);
        $fixedKey = $this->fixKey(key: (new Convert())->str2long(string: $key));
        $length = count(value: $vector) - 1;
        $first = $vector[0];
        $sum = (new Number())->floatToInt32(number: floor(num: 6 + 52 / ($length + 1)) * self::DELTA);

        while (0 !== $sum) {
            $shiftRight = $sum >> 2 & 3;

            for ($pointer = $length; $pointer > 0; $pointer--) {
                $last = $vector[$pointer - 1];
                $first = $vector[$pointer] = (new Number())->floatToInt32(number: $vector[$pointer] - $this->mx(sum: $sum, first: $first, last: $last, pointer: $pointer, right: $shiftRight, key: $fixedKey));
            }

            $last = $vector[$length];
            $first = $vector[0] = (new Number())->floatToInt32(number: $vector[0] - $this->mx(sum: $sum, first: $first, last: $last, pointer: $pointer, right: $shiftRight, key: $fixedKey));
            $sum = (new Number())->floatToInt32(number: $sum - self::DELTA);
        }

        return (new Convert())->long2str(array: $vector, wide: true);
    }

    /**
     * @param array<int, int> $key
     */
    private function mx(int $sum, int $first, int $last, int $pointer, int $right, array $key): int
    {
        return ((($last >> 5 & 0x07FFFFFF) ^ $first << 2) + (($first >> 3 & 0x1FFFFFFF) ^ $last << 4)) ^ (($sum ^ $first) + ($key[$pointer & 3 ^ $right] ^ $last));
    }

    /**
     * @param array<int, int> $key
     *
     * @return array<int, int>
     */
    private function fixKey(array $key): array
    {
        if (count(value: $key) < 4) {
            for ($i = count(value: $key); $i < 4; $i++) {
                $key[$i] = 0;
            }
        }

        return $key;
    }
}
