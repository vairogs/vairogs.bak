<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use JetBrains\PhpStorm\Pure;
use Vairogs\Utils\Twig\Attribute;
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

class Char
{
    final public const LCFIRST = 'lcfirst';
    final public const UCFIRST = 'ucfirst';

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function fromCamelCase(string $string, string $separator = '_'): string
    {
        return strtolower(string: preg_replace(pattern: '#(?!^)[[:upper:]]+#', replacement: $separator . '$0', subject: $string));
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function toSnakeCase(string $string, bool $skipCamel = false): string
    {
        $string = preg_replace(pattern: [
            '#([A-Z\d]+)([A-Z][a-z])#',
            '#([a-z\d])([A-Z])#',
        ], replacement: '\1_\2', subject: $skipCamel ? $string : self::toCamelCase(string: $string));

        return strtolower(string: str_replace(search: '-', replace: '_', subject: $string));
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function toCamelCase(string $string, string $function = self::LCFIRST): string
    {
        return preg_replace(pattern: '#\s+#', replacement: '', subject: $function(string: ucwords(string: strtolower(string: str_replace(search: '_', replace: ' ', subject: $string)))));
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    #[Pure]
    public static function sanitizeFloat(string $string): float
    {
        return (float) filter_var(value: $string, filter: FILTER_SANITIZE_NUMBER_FLOAT, options: FILTER_FLAG_ALLOW_FRACTION);
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function long2str(array $array, bool $wide = false): string
    {
        $length = count(value: $array);
        $shiftLeft = $length << 2;

        if ($wide) {
            $lastElement = $array[$length - 1];
            $shiftLeft -= 4;

            if ($lastElement < $shiftLeft - 3 || $lastElement > $shiftLeft) {
                return '';
            }

            $shiftLeft = $lastElement;
        }

        $string = [];
        for ($i = 0; $i < $length; $i++) {
            $string[$i] = pack('V', $array[$i]);
        }

        $result = implode(separator: '', array: $string);

        if ($wide) {
            return substr(string: $result, offset: 0, length: $shiftLeft);
        }

        return $result;
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function str2long(string $string, bool $wide = false): array
    {
        $array = array_values(unpack(format: 'V*', string: $string . str_repeat(string: "\0", times: (4 - ($length = strlen(string: $string)) % 4) & 3)));

        if ($wide) {
            /* @noinspection PhpAutovivificationOnFalseValuesInspection */
            $array[] = $length;
        }

        return $array;
    }
}
