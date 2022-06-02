<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use Closure;
use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use Vairogs\Extra\Constants\Enum\Order;
use Vairogs\Twig\Attribute\TwigFilter;
use Vairogs\Twig\Attribute\TwigFunction;
use function array_key_exists;
use function array_slice;
use function count;
use function current;
use function is_array;
use function is_object;
use function property_exists;
use function round;
use function usort;

/** @noinspection TypoSafeNamingInspection */
final class Sort
{
    #[TwigFunction]
    #[TwigFilter]
    public function swap(mixed &$foo, mixed &$bar): void
    {
        if ($foo === $bar) {
            return;
        }

        $tmp = $foo;
        $foo = $bar;
        $bar = $tmp;
    }

    #[TwigFunction]
    #[TwigFilter]
    public function bubbleSort(array &$array): void
    {
        $count = count(value: $array);
        for ($foo = 0; $foo < $count; $foo++) {
            for ($bar = 0; $bar < $count - 1; $bar++) {
                if ($bar < $count && $array[$bar] > $array[$bar + 1]) {
                    $this->swapArray(array: $array, foo: $bar, bar: $bar + 1);
                }
            }
        }
    }

    #[TwigFunction]
    #[TwigFilter]
    public function swapArray(array &$array, mixed $foo, mixed $bar): void
    {
        if ($array[$foo] === $array[$bar]) {
            return;
        }

        $tmp = $array[$foo];
        $array[$foo] = $array[$bar];
        $array[$bar] = $tmp;
    }

    #[TwigFunction]
    #[TwigFilter]
    public function mergeSort(array $array): array
    {
        if (1 >= count(value: $array)) {
            return $array;
        }

        $middle = (int) round(num: count(value: $array) / 2);
        $left = array_slice(array: $array, offset: 0, length: $middle);
        $right = array_slice(array: $array, offset: $middle);

        $left = $this->mergeSort(array: $left);
        $right = $this->mergeSort(array: $right);

        return $this->merge(left: $left, right: $right);
    }

    /** @throws InvalidArgumentException */
    #[TwigFunction]
    #[TwigFilter]
    public function sort(array|object $data, string $parameter, Order $order = Order::ASC): object|array
    {
        if (count(value: $data) < 2) {
            return $data;
        }

        $data = (array) $data;
        if (!$this->isSortable(item: current(array: $data), field: $parameter)) {
            throw new InvalidArgumentException(message: "Sorting parameter doesn't exist in sortable variable");
        }

        usort(array: $data, callback: $this->usort(parameter: $parameter, order: $order));

        return $data;
    }

    #[TwigFunction]
    #[TwigFilter]
    #[Pure]
    public function isSortable(mixed $item, int|string $field): bool
    {
        if (is_array(value: $item)) {
            return array_key_exists(key: $field, array: $item);
        }

        if (is_object(value: $item)) {
            return isset($item->{$field}) || property_exists(object_or_class: $item, property: $field);
        }

        return false;
    }

    #[TwigFunction]
    #[TwigFilter]
    public function usort(string $parameter, Order $order): Closure
    {
        return static function (array|object $first, array|object $second) use ($parameter, $order): int {
            if (($firstSort = (new Php())->getParameter(variable: $first, key: $parameter)) === ($secondSort = (new Php())->getParameter(variable: $second, key: $parameter))) {
                return 0;
            }

            $flip = (Order::DESC === $order) ? -1 : 1;

            if ($firstSort > $secondSort) {
                return $flip;
            }

            return -1 * $flip;
        };
    }

    private function merge(array $left, array $right): array
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
}
