<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use JetBrains\PhpStorm\Pure;
use Vairogs\Core\Attribute\TwigFilter;
use Vairogs\Core\Attribute\TwigFunction;
use Vairogs\Extra\Constants\Enum\CamelCase;

use function filter_var;
use function preg_replace;
use function str_replace;
use function strtolower;
use function ucwords;

use const FILTER_FLAG_ALLOW_FRACTION;
use const FILTER_SANITIZE_NUMBER_FLOAT;

final class Char
{
    #[TwigFunction]
    #[TwigFilter]
    public function fromCamelCase(string $string, string $separator = '_'): string
    {
        return strtolower(string: (string) preg_replace(pattern: '#(?!^)[[:upper:]]+#', replacement: $separator . '$0', subject: $string));
    }

    #[TwigFunction]
    #[TwigFilter]
    public function toSnakeCase(string $string, bool $skipCamel = false): string
    {
        $string = preg_replace(pattern: [
            '#([A-Z\d]+)([A-Z][a-z])#',
            '#([a-z\d])([A-Z])#',
        ], replacement: '\1_\2', subject: $skipCamel ? $string : $this->toCamelCase(string: $string));

        return strtolower(string: str_replace(search: '-', replace: '_', subject: (string) $string));
    }

    #[TwigFunction]
    #[TwigFilter]
    public function toCamelCase(string $string, CamelCase $function = CamelCase::LCFIRST): string
    {
        return (string) preg_replace(pattern: '#\s+#', replacement: '', subject: ($function->value)(string: ucwords(string: strtolower(string: str_replace(search: '_', replace: ' ', subject: $string)))));
    }

    #[TwigFunction]
    #[TwigFilter]
    #[Pure]
    public function sanitizeFloat(string $string): float
    {
        return (float) filter_var(value: $string, filter: FILTER_SANITIZE_NUMBER_FLOAT, options: FILTER_FLAG_ALLOW_FRACTION);
    }
}
