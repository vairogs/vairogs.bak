<?php declare(strict_types = 1);

namespace Vairogs\Core;

use Simple\To\Implement\DependencyInjection\Functions as Base;

use const PHP_INT_MAX;

final class Functions
{
    public function makeOneDimension(array $array, string $base = '', string $separator = '.', bool $onlyLast = false, int $depth = 0, int $maxDepth = PHP_INT_MAX, array $result = []): array
    {
        return (new Base())->makeOneDimension(array: $array, base: $base, separator: $separator, onlyLast: $onlyLast, depth: $depth, maxDepth: $maxDepth, result: $result);
    }
}
