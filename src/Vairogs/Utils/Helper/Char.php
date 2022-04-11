<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use JetBrains\PhpStorm\Pure;
use Vairogs\Utils\Twig\Attribute;
use function filter_var;
use function preg_replace;
use function str_replace;
use function strtolower;
use function ucwords;
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
}
