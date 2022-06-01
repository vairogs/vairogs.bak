<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\PropertyInfo\Type;
use Vairogs\Extra\Constants\Enum\StartsEnds;
use Vairogs\Twig\Attribute;
use function array_diff;
use function array_filter;
use function array_flip;
use function array_intersect_key;
use function array_keys;
use function array_map;
use function array_unique;
use function array_values;
use function get_debug_type;
use function in_array;
use function is_array;
use function is_object;
use function str_ends_with;
use function str_starts_with;
use const ARRAY_FILTER_USE_KEY;

final class Iteration
{
    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function isEmpty(mixed $variable, bool $result = true): bool
    {
        if (is_array(value: $variable) && [] !== $variable) {
            foreach ($variable as $value) {
                $result = $this->isEmpty(variable: $value, result: $result);
            }

            return $result;
        }

        return empty($variable);
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
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

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function uniqueMap(array &$array): void
    {
        $array = array_map(callback: 'unserialize', array: array_unique(array: array_map(callback: 'serialize', array: $array)));
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
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

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
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

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function isAssociative(mixed $array): bool
    {
        if (!is_array(value: $array) || [] === $array) {
            return false;
        }

        return !array_is_list($array);
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
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
    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function arrayFlipRecursive(array $input = []): array
    {
        $result = [];

        foreach ($input as $key => $element) {
            if (is_array(value: $element) || is_object(value: $element)) {
                $result[$key] = $element;
            } elseif (in_array(needle: get_debug_type(value: $element), haystack: [Type::BUILTIN_TYPE_INT, Type::BUILTIN_TYPE_STRING], strict: true)) {
                $result[$element] = $key;
            } else {
                throw new InvalidArgumentException(message: 'Value should be array, object, string or integer');
            }
        }

        return $result;
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function removeFromArray(array &$input, mixed $value): void
    {
        $input = array_diff($input, [$value]);
    }

    /** @throws InvalidArgumentException */
    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function arrayValuesFiltered(array $input, string $with, StartsEnds $enum = StartsEnds::STARTS): array
    {
        return match ($enum) {
            StartsEnds::STARTS => array_values(array: $this->filterKeyStartsWith(input: $input, startsWith: $with)),
            StartsEnds::ENDS => array_values(array: $this->filterKeyEndsWith(input: $input, endsWith: $with))
        };
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function filterKeyStartsWith(array $input, string $startsWith): array
    {
        return array_filter(array: $input, callback: static fn ($key) => str_starts_with(haystack: $key, needle: $startsWith), mode: ARRAY_FILTER_USE_KEY);
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function filterKeyEndsWith(array $input, string $endsWith): array
    {
        return array_filter(array: $input, callback: static fn ($key) => str_ends_with(haystack: $key, needle: $endsWith), mode: ARRAY_FILTER_USE_KEY);
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function getIfSet(array $input, mixed $key): mixed
    {
        return $input[$key] ?? null;
    }
}
