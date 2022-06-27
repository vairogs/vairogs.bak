<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use Vairogs\Extra\Constants\Enum\StartsEnds;
use Vairogs\Twig\Attribute\TwigFilter;
use Vairogs\Twig\Attribute\TwigFunction;

use function array_diff;
use function array_filter;
use function array_flip;
use function array_intersect_key;
use function array_keys;
use function array_map;
use function array_unique;
use function array_values;
use function gettype;
use function is_array;
use function str_ends_with;
use function str_starts_with;

use const ARRAY_FILTER_USE_KEY;

final class Iteration
{
    #[TwigFunction]
    #[TwigFilter]
    public function isEmpty(mixed $variable, bool $result = true): bool
    {
        if (is_array(value: $variable) && [] !== $variable) {
            foreach ($variable as $item) {
                $result = $this->isEmpty(variable: $item, result: $result);
            }

            return $result;
        }

        return empty($variable);
    }

    #[TwigFunction]
    #[TwigFilter]
    public function makeMultiDimensional(array $array): array
    {
        if ($this->isMultiDimensional(keys: $array)) {
            return $array;
        }

        $result = [];

        /* @noinspection MissUsingForeachInspection */
        foreach ($array as $item) {
            $result[][] = $item;
        }

        return $result;
    }

    #[TwigFunction]
    #[TwigFilter]
    public function uniqueMap(array &$array): void
    {
        $array = array_map(callback: 'unserialize', array: array_unique(array: array_map(callback: 'serialize', array: $array)));
    }

    #[TwigFunction]
    #[TwigFilter]
    #[Pure]
    public function unique(array $input, bool $keepKeys = false): array
    {
        if ($keepKeys) {
            return array_unique(array: $input);
        }

        if ($this->isMultiDimensional(keys: $input)) {
            return $input;
        }

        return array_keys(array: array_flip(array: $input));
    }

    #[TwigFunction]
    #[TwigFilter]
    #[Pure]
    public function isMultiDimensional(array $keys = []): bool
    {
        foreach ($keys as $key) {
            if (is_array(value: $key)) {
                return true;
            }
        }

        return false;
    }

    #[TwigFunction]
    #[TwigFilter]
    public function isAssociative(mixed $array): bool
    {
        if (!is_array(value: $array) || [] === $array) {
            return false;
        }

        return !array_is_list($array);
    }

    #[TwigFunction]
    #[TwigFilter]
    public function arrayIntersectKeyRecursive(array $first = [], array $second = []): array
    {
        $result = array_intersect_key($first, $second);

        foreach (array_keys(array: $result) as $key) {
            if (is_array(value: $first[$key]) && is_array(value: $second[$key])) {
                $result[$key] = $this->arrayIntersectKeyRecursive(first: $first[$key], second: $second[$key]);
            }
        }

        return $result;
    }

    /** @throws InvalidArgumentException */
    #[TwigFunction]
    #[TwigFilter]
    public function arrayFlipRecursive(array $input = []): array
    {
        $result = [[]];

        foreach ($input as $key => $element) {
            $result[] = match (gettype(value: $element)) {
                'array', 'object' => [$key => $element, ],
                'integer', 'string' => [$element => $key, ],
                default => throw new InvalidArgumentException(message: 'Value should be array, object, string or integer')
            };
        }

        return array_replace(...$result);
    }

    #[TwigFunction]
    #[TwigFilter]
    public function removeFromArray(array &$input, mixed $value): void
    {
        $input = array_diff($input, [$value]);
    }

    /** @throws InvalidArgumentException */
    #[TwigFunction]
    #[TwigFilter]
    public function arrayValuesFiltered(array $input, string $with, StartsEnds $startsEnds = StartsEnds::STARTS): array
    {
        return match ($startsEnds) {
            StartsEnds::STARTS => array_values(array: $this->filterKeyStartsWith(input: $input, startsWith: $with)),
            StartsEnds::ENDS => array_values(array: $this->filterKeyEndsWith(input: $input, endsWith: $with))
        };
    }

    #[TwigFunction]
    #[TwigFilter]
    public function filterKeyStartsWith(array $input, string $startsWith): array
    {
        return array_filter(array: $input, callback: static fn ($key) => str_starts_with(haystack: (string) $key, needle: $startsWith), mode: ARRAY_FILTER_USE_KEY);
    }

    #[TwigFunction]
    #[TwigFilter]
    public function filterKeyEndsWith(array $input, string $endsWith): array
    {
        return array_filter(array: $input, callback: static fn ($key) => str_ends_with(haystack: $key, needle: $endsWith), mode: ARRAY_FILTER_USE_KEY);
    }

    #[TwigFunction]
    #[TwigFilter]
    public function getIfSet(array $input, mixed $key): mixed
    {
        return $input[$key] ?? null;
    }

    #[TwigFunction]
    #[TwigFilter]
    public function getFirstMatchAsString(array $keys, array $haystack): ?string
    {
        foreach ($keys as $key) {
            if (isset($haystack[$key])) {
                return (string) $haystack[$key];
            }
        }

        return null;
    }
}
