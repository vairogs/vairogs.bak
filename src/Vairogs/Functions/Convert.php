<?php declare(strict_types = 1);

namespace Vairogs\Functions;

use function array_pop;
use function array_values;
use function count;
use function implode;
use function pack;
use function str_repeat;
use function strlen;
use function substr;
use function unpack;

final class Convert
{
    public function long2str(array $array, bool $wide = false): string
    {
        $length = count(value: $array);
        $string = [];

        for ($i = 0; $i < $length; $i++) {
            $string[$i] = pack('V', $array[$i]);
        }

        if ($wide) {
            /* @noinspection PhpRedundantOptionalArgumentInspection */
            return substr(string: implode(separator: '', array: $string), offset: 0, length: (int) $array[$length - 1]);
        }

        /* @noinspection PhpRedundantOptionalArgumentInspection */
        return implode(separator: '', array: $string);
    }

    /**
     * @return int[]
     */
    public function str2long(string $string, bool $wide = false): array
    {
        $array = array_values(unpack(format: 'V*', string: $string . str_repeat(string: "\0", times: (4 - ($length = strlen(string: $string)) % 4) & 3)));

        if ($wide) {
            /* @noinspection PhpAutovivificationOnFalseValuesInspection */
            $array[] = $length;
        }

        return $array;
    }

    public function char2byte(string $char): int
    {
        $pack = unpack(format: 'c', string: $char);

        return (int) array_pop(array: $pack);
    }

    public function byte2char(int $byte): string
    {
        return pack('c', $byte);
    }
}
