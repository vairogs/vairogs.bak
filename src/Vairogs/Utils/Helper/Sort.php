<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use Vairogs\Extra\Constants\Symbol;
use Vairogs\Utils\Twig\Attribute;
use function array_key_exists;
use function array_slice;
use function count;
use function current;
use function is_array;
use function is_iterable;
use function is_object;
use function method_exists;
use function property_exists;
use function round;
use function strtoupper;
use function usort;

/** @noinspection TypoSafeNamingInspection */
final class Sort
{
    private static string $field = '';

    #[Attribute\TwigFunction]
    public static function swap(mixed &$foo, mixed &$bar): void
    {
        if ($foo === $bar) {
            return;
        }

        $tmp = $foo;
        $foo = $bar;
        $bar = $tmp;
    }

    #[Attribute\TwigFilter]
    public static function bubbleSort(array &$array): void
    {
        $count = count(value: $array);
        for ($foo = 0; $foo < $count; $foo++) {
            for ($bar = 0; $bar < $count - 1; $bar++) {
                if ($bar < $count && $array[$bar] > $array[$bar + 1]) {
                    self::swapArray(array: $array, foo: $bar, bar: $bar + 1);
                }
            }
        }
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function swapArray(array &$array, mixed $foo, mixed $bar): void
    {
        if ($array[$foo] === $array[$bar]) {
            return;
        }

        $tmp = $array[$foo];
        $array[$foo] = $array[$bar];
        $array[$bar] = $tmp;
    }

    #[Attribute\TwigFilter]
    public static function mergeSort(array $array): array
    {
        if (1 >= count(value: $array)) {
            return $array;
        }

        $middle = (int) round(num: count(value: $array) / 2);
        $left = array_slice(array: $array, offset: 0, length: $middle);
        $right = array_slice(array: $array, offset: $middle);

        $left = self::mergeSort(array: $left);
        $right = self::mergeSort(array: $right);

        return self::merge(left: $left, right: $right);
    }

    #[Attribute\TwigFilter]
    public static function sort(iterable|Collection $data, string $parameter, string $order = Criteria::ASC): array
    {
        if ($data instanceof Collection && method_exists(object_or_class: $data, method: 'toArray')) {
            $data = $data->toArray();
        }

        if (!is_iterable(value: $data)) {
            throw new InvalidArgumentException(message: 'Only iterable variables can be sorted');
        }

        if (count(value: $data) < 2) {
            return $data;
        }

        $data = (array) $data;
        if (!self::isSortable(item: current(array: $data), field: $parameter)) {
            throw new InvalidArgumentException(message: "Sorting parameter doesn't exist in sortable variable");
        }

        usort(array: $data, callback: self::usort(parameter: $parameter, order: strtoupper(string: $order)));

        return $data;
    }

    #[Attribute\TwigFunction]
    #[Pure]
    public static function isSortable(mixed $item, int|string $field): bool
    {
        if (is_array(value: $item)) {
            return array_key_exists(key: $field, array: $item);
        }

        if (is_object(value: $item)) {
            return isset($item->{$field}) || property_exists(object_or_class: $item, property: $field);
        }

        return false;
    }

    #[Attribute\TwigFilter]
    public static function usort(string $parameter, string $order): callable
    {
        return static function (array|object $a, array|object $b) use ($parameter, $order): int {
            if (($aSort = Php::getParameter(variable: $a, key: $parameter)) === ($bSort = Php::getParameter(variable: $b, key: $parameter))) {
                return 0;
            }

            $flip = (Criteria::DESC === $order) ? -1 : 1;

            if ($aSort > $bSort) {
                return $flip;
            }

            return -1 * $flip;
        };
    }

    #[Attribute\TwigFilter]
    public static function sortLatvian(array &$names, string $field): bool
    {
        self::$field = $field;
        $result = usort(array: $names, callback: [
            self::class,
            'compareLatvian',
        ]);
        self::$field = '';

        return $result;
    }

    private static function merge(array $left, array $right): array
    {
        $result = [];
        $i = $j = 0;

        $leftCount = count(value: $left);
        $rightCount = count(value: $right);

        while ($i < $leftCount && $j < $rightCount) {
            if ($left[$i] > $right[$j]) {
                $result[] = $right[$j];
                $j++;
            } else {
                $result[] = $left[$i];
                $i++;
            }
        }

        while ($i < $leftCount) {
            $result[] = $left[$i];
            $i++;
        }

        while ($j < $rightCount) {
            $result[] = $right[$j];
            $j++;
        }

        return $result;
    }

    private static function compareLatvian(array|object $a, array|object $b): int
    {
        $a = mb_strtolower(string: Php::getParameter(variable: $a, key: self::$field));
        $b = mb_strtolower(string: Php::getParameter(variable: $b, key: self::$field));

        $len = mb_strlen(string: $a);

        for ($i = 0; $i < $len; $i++) {
            if (mb_substr(string: $a, start: $i, length: 1) === mb_substr(string: $b, start: $i, length: 1)) {
                continue;
            }

            if ($i > mb_strlen(string: $b) || mb_strpos(haystack: Symbol::LV_LOWERCASE, needle: mb_substr(string: $a, start: $i, length: 1)) > mb_strpos(haystack: Symbol::LV_LOWERCASE, needle: mb_substr(string: $b, start: $i, length: 1))) {
                return 1;
            }

            return -1;
        }

        return 0;
    }
}
