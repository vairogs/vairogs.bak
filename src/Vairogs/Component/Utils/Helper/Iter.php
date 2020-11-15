<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Helper;

use InvalidArgumentException;
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
use const ARRAY_FILTER_USE_KEY;

class Iter
{
    /**
     * @param $variable
     *
     * @return bool
     */
    public static function isEmpty($variable): bool
    {
        $result = true;
        if (!empty($variable) && is_array($variable)) {
            foreach ($variable as $value) {
                $result = $result && self::isEmpty($value);
            }
        } else {
            $result = empty($variable);
        }

        return $result;
    }

    /**
     * @param $array
     */
    public static function uniqueMap(array &$array): void
    {
        $array = array_map('\unserialize', array_unique(array_map('\serialize', $array)));
    }

    /**
     * @param array $input
     * @param bool $keepKeys
     *
     * @return array
     */
    public static function unique(array $input, bool $keepKeys = false): array
    {
        if (true === $keepKeys) {
            return array_unique($input);
        }

        return array_keys(array_flip($input));
    }

    /**
     * @param array $keys
     *
     * @return bool
     */
    public static function isMultiDimensional(array $keys = []): bool
    {
        foreach ($keys as $key) {
            if (is_array($key)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $keys
     *
     * @return bool
     */
    public static function isAnyKeyNull(array $keys = []): bool
    {
        foreach ($keys as $key) {
            if (null === $key) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $array
     * @param string $base
     * @param string $separator
     * @param bool $onlyLast
     *
     * @return array
     */
    public static function makeOneDimension(array $array, string $base = '', string $separator = '.', bool $onlyLast = false): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            $key = $base ? $base . '.' . $key : $key;
            if (is_array($value) && self::isAssociative($value)) {
                foreach (self::makeOneDimension($value, (string)$key, $separator) as $ik => $iv) {
                    $result[$ik] = $iv;
                }

                if (true === $onlyLast) {
                    continue;
                }
            }
            $result[$key] = $value;
        }

        return $result;
    }

    /**
     * @param array $array
     *
     * @return bool
     */
    public static function isAssociative(array $array): bool
    {
        if ([] === $array) {
            return false;
        }

        return array_keys($array) !== range(0, count($array) - 1);
    }

    /**
     * @param array $first
     * @param array $second
     *
     * @return array
     */
    public static function arrayIntersectKeyRecursive(array $first = [], array $second = []): array
    {
        $result = array_intersect_key($first, $second);
        foreach ($result as $key => $value) {
            if (is_array($first[$key]) && is_array($second[$key])) {
                $result[$key] = self::arrayIntersectKeyRecursive($first[$key], $second[$key]);
            }
        }

        return $result;
    }

    /**
     * @param array $input
     *
     * @return array
     * @throws InvalidArgumentException
     */
    public static function arrayFlipRecursive(array $input = []): array
    {
        $result = [];
        foreach ($input as $key => $element) {
            if (is_array($element)) {
                $result[$key] = self::arrayFlipRecursive($element);
            } elseif (is_object($element)) {
                $result[$key] = self::arrayFlipRecursive((array)$element);
            } elseif (in_array(gettype($element), [
                'integer',
                'string',
            ], true)) {
                $result[$element] = $key;
            } else {
                throw new InvalidArgumentException('Value should be array, string or integer.');
            }
        }

        return $result;
    }

    /**
     * @param array $input
     * @param $value
     */
    public static function removeFromArray(array &$input, $value): void
    {
        if (in_array($value, $input, true)) {
            foreach ($input as $key => $item) {
                if ($item === $value) {
                    unset($input[$key]);
                }
            }
        }
    }

    /**
     * @param array $input
     * @param string $with
     * @param string $type
     *
     * @return array
     * @throws InvalidArgumentException
     */
    public static function arrayValuesFiltered(array $input, string $with, string $type = 'starts'): array
    {
        switch ($type) {
            case 'starts':
                return array_values(self::filterKeyStartsWith($input, $with));
            case 'ends':
                return array_values(self::filterKeyEndsWith($input, $with));
            default:
                throw new InvalidArgumentException(sprintf('Invalid type "%s", allowed types are "starts" and "ends"', $type));
        }
    }

    /**
     * @param array $input
     * @param string $startsWith
     *
     * @return array
     */
    public static function filterKeyStartsWith(array $input, string $startsWith): array
    {
        return array_filter($input, static function ($key) use ($startsWith) {
            return Text::starts($key, $startsWith, true);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * @param array $input
     * @param string $endsWith
     *
     * @return array
     */
    public static function filterKeyEndsWith(array $input, string $endsWith): array
    {
        return array_filter($input, static function ($key) use ($endsWith) {
            return Text::ends($key, $endsWith, true);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * @param array $input
     * @param $key
     *
     * @return mixed|null
     */
    public static function getIfNotEmpty(array $input, $key)
    {
        if (!isset($input[$key])) {
            return null;
        }

        return !empty($input[$key]) ? $input[$key] : null;
    }
}
