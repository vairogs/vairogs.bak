<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use JetBrains\PhpStorm\Pure;
use Vairogs\Utils\Twig\Attribute;
use function preg_replace;
use function str_pad;
use function str_replace;
use function strtolower;
use function ucwords;
use const STR_PAD_LEFT;

class Char
{
    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    #[Pure]
    public static function pad(string $input, int $length, string $padding, int $type = STR_PAD_LEFT): string
    {
        return str_pad(string: $input, length: $length, pad_string: $padding, pad_type: $type);
    }

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
    public static function toCamelCase(string $string, bool $lowFirst = true): string
    {
        $function = true === $lowFirst ? 'lcfirst' : 'ucfirst';

        return preg_replace(pattern: '#\s+#', replacement: '', subject: $function(string: ucwords(string: strtolower(string: str_replace(search: '_', replace: ' ', subject: $string)))));
    }
}
