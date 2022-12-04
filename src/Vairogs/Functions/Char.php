<?php declare(strict_types = 1);

namespace Vairogs\Functions;

use JetBrains\PhpStorm\Pure;

use function filter_var;
use function lcfirst;
use function preg_replace;
use function str_replace;
use function strtolower;
use function ucfirst;
use function ucwords;

use const FILTER_FLAG_ALLOW_FRACTION;
use const FILTER_SANITIZE_NUMBER_FLOAT;

final class Char
{
    public function fromCamelCase(string $string, string $separator = '_'): string
    {
        return strtolower(string: (string) preg_replace(pattern: '#(?!^)[[:upper:]]+#', replacement: $separator . '$0', subject: $string));
    }

    public function toSnakeCase(string $string, bool $skipCamel = false): string
    {
        $string = preg_replace(pattern: [
            '#([A-Z\d]+)([A-Z][a-z])#',
            '#([a-z\d])([A-Z])#',
        ], replacement: '\1_\2', subject: $skipCamel ? $string : $this->toCamelCaseLCFisrt(string: $string));

        return strtolower(string: str_replace(search: '-', replace: '_', subject: (string) $string));
    }

    public function toCamelCaseLCFisrt(string $string): string
    {
        return (string) preg_replace(pattern: '#\s+#', replacement: '', subject: lcfirst(string: ucwords(string: strtolower(string: str_replace(search: '_', replace: ' ', subject: $string)))));
    }

    public function toCamelCaseLCFisrtUCFirst(string $string): string
    {
        return (string) preg_replace(pattern: '#\s+#', replacement: '', subject: ucfirst(string: ucwords(string: strtolower(string: str_replace(search: '_', replace: ' ', subject: $string)))));
    }

    #[Pure]
    public function sanitizeFloat(string $string): float
    {
        return (float) filter_var(value: $string, filter: FILTER_SANITIZE_NUMBER_FLOAT, options: FILTER_FLAG_ALLOW_FRACTION);
    }
}
