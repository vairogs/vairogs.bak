<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Helper;

use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use Vairogs\Component\Utils\Twig\Annotation;
use function array_filter;
use function array_flip;
use function array_intersect_key;
use function array_keys;
use function array_map;
use function array_unique;
use function array_values;
use function count;
use function gettype;
use function in_array;
use function is_array;
use function is_object;
use function range;
use function sprintf;
use function str_ends_with;
use function str_starts_with;

class Iteration
{
    public const DOT = '.';

    #[Annotation\TwigFunction]
    public static function isEmpty(mixed $variable): bool
    {
        $result = true;

        if (!empty($variable) && is_array(value: $variable)) {
            foreach ($variable as $value) {
                $result = $result && self::isEmpty(variable: $value);
            }
        } else {
            $result = empty($variable);
        }

        return $result;
    }

    #[Annotation\TwigFilter]
    public static function uniqueMap(array &$array): void
    {
        $array = array_map(callback: '\unserialize', array: array_unique(array: array_map(callback: '\serialize', array: $array)));
    }

    #[Annotation\TwigFilter]
    #[Pure]
    public static function unique(array $input, bool $keepKeys = false): array
    {
        if ($keepKeys) {
            return array_unique(array: $input);
        }

        return array_keys(array: array_flip(array: $input));
    }

    #[Annotation\TwigFunction]
    #[Pure]
    public static function isMultiDimensional(array $keys = []): bool
    {
        foreach ($keys as $key) {
            if (is_array(value: $key)) {
                return true;
            }
        }

        return false;
    }

    #[Annotation\TwigFunction]
    #[Pure]
    public static function isAnyKeyNull(array $keys = []): bool
    {
        return in_array(needle: null, haystack: $keys, strict: true);
    }

    #[Annotation\TwigFilter]
    public static function makeOneDimension(array $array, string $base = '', string $separator = '.', bool $onlyLast = false, int $depth = 0, int $maxDepth = PHP_INT_MAX): array
    {
        $result = [];

        if ($depth <= $maxDepth) {
            foreach ($array as $key => $value) {
                $key = ltrim(string: $base . self::DOT . $key, characters: self::DOT);

                if (self::isAssociative(array: $value)) {
                    foreach (self::makeOneDimension(array: $value, base: $key, separator: $separator, depth: $depth + 1, maxDepth: $maxDepth) as $itemKey => $itemValue) {
                        $result[$itemKey] = $itemValue;
                    }

                    if ($onlyLast) {
                        continue;
                    }
                }

                $result[$key] = $value;
            }
        }

        return $result;
    }

    #[Annotation\TwigFunction]
    #[Pure]
    public static function isAssociative(mixed $array): bool
    {
        if (!is_array(value: $array) || [] === $array) {
            return false;
        }

        return array_keys(array: $array) !== range(start: 0, end: count(value: $array) - 1);
    }

    #[Annotation\TwigFilter]
    public static function arrayIntersectKeyRecursive(array $first = [], array $second = []): array
    {
        $result = array_intersect_key(array1: $first, array2: $second);

        foreach (array_keys(array: $result) as $key) {
            if (is_array(value: $first[$key]) && is_array(value: $second[$key])) {
                $result[$key] = self::arrayIntersectKeyRecursive(first: $first[$key], second: $second[$key]);
            }
        }

        return $result;
    }

    /**
     * @throws InvalidArgumentException
     */
    #[Annotation\TwigFilter]
    public static function arrayFlipRecursive(array $input = []): array
    {
        $result = [];

        foreach ($input as $key => $element) {
            if (is_array(value: $element) || is_object(value: $element)) {
                $result[$key] = self::arrayFlipRecursive(input: (array) $element);
            } elseif (in_array(needle: gettype(value: $element), haystack: [
                'integer',
                'string',
            ], strict: true)) {
                $result[$element] = $key;
            } else {
                throw new InvalidArgumentException(message: 'Value should be array, string or integer.');
            }
        }

        return $result;
    }

    #[Annotation\TwigFilter]
    public static function removeFromArray(array &$input, mixed $value): void
    {
        if (in_array(needle: $value, haystack: $input, strict: true)) {
            foreach ($input as $key => $item) {
                if ($item === $value) {
                    unset($input[$key]);
                }
            }
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    #[Annotation\TwigFilter]
    public static function arrayValuesFiltered(array $input, string $with, string $type = 'starts'): array
    {
        return match ($type) {
            'starts' => array_values(array: self::filterKeyStartsWith(input: $input, startsWith: $with)),
            'ends' => array_values(array: self::filterKeyEndsWith(input: $input, endsWith: $with)),
            default => throw new InvalidArgumentException(message: sprintf('Invalid type "%s", allowed types are "starts" and "ends"', $type)),
        };
    }

    #[Annotation\TwigFilter]
    public static function filterKeyStartsWith(array $input, string $startsWith): array
    {
        return array_filter(array: $input, callback: static fn ($key) => str_starts_with(haystack: $key, needle: $startsWith), mode: ARRAY_FILTER_USE_KEY);
    }

    #[Annotation\TwigFilter]
    public static function filterKeyEndsWith(array $input, string $endsWith): array
    {
        return array_filter(array: $input, callback: static fn ($key) => str_ends_with(haystack: $key, needle: $endsWith), mode: ARRAY_FILTER_USE_KEY);
    }

    #[Annotation\TwigFilter]
    public static function getIfNotEmpty(array $input, mixed $key): mixed
    {
        if (!isset($input[$key])) {
            return null;
        }

        return empty($input[$key]) ? null : $input[$key];
    }
}
