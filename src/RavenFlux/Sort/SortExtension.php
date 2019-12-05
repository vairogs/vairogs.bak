<?php

namespace RavenFlux\Twig;

use Doctrine\Common\Collections\Collection;
use InvalidArgumentException;
use Twig_Extension;
use Twig_SimpleFilter;
use function array_key_exists;
use function count;
use function current;
use function is_array;
use function is_object;
use function method_exists;
use function property_exists;
use function strtoupper;
use function ucfirst;
use function usort;
use const false;
use const null;

class SortExtension extends Twig_Extension
{
    protected const ASC = 'ASC';
    protected const DESC = 'DESC';

    public function getFilters(): array
    {
        return [
            new Twig_SimpleFilter('usort', [$this, 'usortFunction']),
        ];
    }

    public function usortFunction($data, $parameter = null, $order = self::ASC): array
    {
        if ($data instanceof Collection) {
            $data = $data->toArray();
        }

        if (!is_array($data)) {
            throw new InvalidArgumentException('Only iterable variables can be sorted');
        }

        if (count($data) < 2) {
            return $data;
        }

        if (null === $parameter) {
            throw new InvalidArgumentException('No sorting parameter pased');
        }

        if (!self::isSortable(current($data), $parameter)) {
            throw new InvalidArgumentException('Sorting parameter doesn\'t exist in sortable variable');
        }

        $order = strtoupper($order);

        @usort($content, function ($a, $b) use ($parameter, $order) {
            $flip = ($order === self::DESC) ? -1 : 1;
            if (is_array($a)) {
                $a_sort_value = $a[$parameter];
            } elseif (method_exists($a, 'get'.ucfirst($parameter))) {
                $a_sort_value = $a->{'get'.ucfirst($parameter)}();
            } else {
                $a_sort_value = $a->$parameter;
            }
            if (is_array($b)) {
                $b_sort_value = $b[$parameter];
            } elseif (method_exists($b, 'get'.ucfirst($parameter))) {
                $b_sort_value = $b->{'get'.ucfirst($parameter)}();
            } else {
                $b_sort_value = $b->$parameter;
            }
            if ($a_sort_value === $b_sort_value) {
                return 0;
            }
            if ($a_sort_value > $b_sort_value) {
                return $flip;
            }

            return (-1 * $flip);
        });

        return $data;
    }

    protected static function isSortable($item, $field): bool
    {
        if (is_array($item)) {
            return array_key_exists($field, $item);
        }
        if (is_object($item)) {
            return isset($item->$field) || property_exists($item, $field);
        }

        return false;
    }
}
