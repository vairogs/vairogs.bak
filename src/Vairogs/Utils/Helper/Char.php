<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use JetBrains\PhpStorm\Pure;
use Vairogs\Extra\Constants\Symbol;
use Vairogs\Twig\Attribute;
use function array_values;
use function count;
use function filter_var;
use function implode;
use function pack;
use function preg_replace;
use function str_repeat;
use function str_replace;
use function strlen;
use function strtolower;
use function substr;
use function ucwords;
use function unpack;
use const FILTER_FLAG_ALLOW_FRACTION;
use const FILTER_SANITIZE_NUMBER_FLOAT;

final class Char
{
    final public const LCFIRST = 'lcfirst';
    final public const UCFIRST = 'ucfirst';

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function fromCamelCase(string $string, string $separator = '_'): string
    {
        return strtolower(string: preg_replace(pattern: '#(?!^)[[:upper:]]+#', replacement: $separator . '$0', subject: $string));
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function toSnakeCase(string $string, bool $skipCamel = false): string
    {
        $string = preg_replace(pattern: [
            '#([A-Z\d]+)([A-Z][a-z])#',
            '#([a-z\d])([A-Z])#',
        ], replacement: '\1_\2', subject: $skipCamel ? $string : $this->toCamelCase(string: $string));

        return strtolower(string: str_replace(search: '-', replace: '_', subject: $string));
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function toCamelCase(string $string, string $function = self::LCFIRST): string
    {
        return preg_replace(pattern: '#\s+#', replacement: '', subject: $function(string: ucwords(string: strtolower(string: str_replace(search: '_', replace: ' ', subject: $string)))));
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    #[Pure]
    public function sanitizeFloat(string $string): float
    {
        return (float) filter_var(value: $string, filter: FILTER_SANITIZE_NUMBER_FLOAT, options: FILTER_FLAG_ALLOW_FRACTION);
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function long2str(array $array, bool $wide = false): string
    {
        $length = count(value: $array);
        $string = [];

        for ($i = 0; $i < $length; $i++) {
            $string[$i] = pack('V', $array[$i]);
        }

        if ($wide) {
            /* @noinspection PhpRedundantOptionalArgumentInspection */
            return substr(string: implode(separator: '', array: $string), offset: 0, length: $array[$length - 1]);
        }

        /* @noinspection PhpRedundantOptionalArgumentInspection */
        return implode(separator: '', array: $string);
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function str2long(string $string, bool $wide = false): array
    {
        $array = array_values(unpack(format: 'V*', string: $string . str_repeat(string: "\0", times: (4 - ($length = strlen(string: $string)) % 4) & 3)));

        if ($wide) {
            /* @noinspection PhpAutovivificationOnFalseValuesInspection */
            $array[] = $length;
        }

        return $array;
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function char2byte(string $char): int
    {
        $pack = unpack(format: 'c', string: $char);

        return (int) array_pop(array: $pack);
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function byte2char(int $byte): string
    {
        return pack('c', $byte);
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function base62Encode(int $number): string
    {
        $base = Symbol::BASIC;
        $length = strlen(string: $base);
        $remainder = $number % $length;
        $result = $base[$remainder];
        $divides = floor(num: $number / $length);

        while ($divides) {
            $remainder = $divides % $length;
            $divides = floor(num: $divides / $length);
            $result = $base[$remainder] . $result;
        }

        return $result;
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function base62Decode(string $string): int
    {
        $base = Symbol::BASIC;
        $length = strlen(string: $base);
        $limit = strlen(string: $string);
        $result = strpos(haystack: $base, needle: $string[0]);

        for ($i = 1; $i < $limit; $i++) {
            $result = $length * $result + strpos(haystack: $base, needle: $string[$i]);
        }

        return $result;
    }
}
