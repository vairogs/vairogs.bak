<?php declare(strict_types = 1);

namespace Vairogs\Functions;

use Closure;
use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;

use function array_key_exists;
use function count;
use function current;
use function is_array;
use function is_object;
use function property_exists;
use function usort;

/** @noinspection TypoSafeNamingInspection */
final class Order
{
    /**
     * @throws InvalidArgumentException
     */
    public function sort(array|object $data, string $parameter, string $order = 'ASC'): object|array
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

    public function usort(string $parameter, string $order): Closure
    {
        return static function (array|object $first, array|object $second) use ($parameter, $order): int {
            if (($firstSort = (new Php())->getParameter(variable: $first, key: $parameter)) === ($secondSort = (new Php())->getParameter(variable: $second, key: $parameter))) {
                return 0;
            }

            $flip = 'DESC' === $order ? -1 : 1;

            if ($firstSort > $secondSort) {
                return $flip;
            }

            return -1 * $flip;
        };
    }
}
