<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Helper;

use JetBrains\PhpStorm\Pure;
use Vairogs\Component\Utils\Twig\Annotation;
use function array_key_exists;
use function array_slice;
use function count;
use function is_array;
use function is_object;
use function property_exists;
use function round;

class Sort
{
    public const ASC = 'ASC';
    public const DESC = 'DESC';

    /**
     * @Annotation\TwigFunction()
     */
    public static function swap(mixed &$foo, mixed &$bar): void
    {
        if ($foo === $bar) {
            return;
        }

        $tmp = $foo;
        $foo = $bar;
        $bar = $tmp;
    }

    /**
     * @Annotation\TwigFilter()
     */
    public static function bubbleSort(array &$array): void
    {
        $count = count($array);
        for ($foo = 0; $foo < $count; $foo++) {
            for ($bar = 0; $bar < $count - 1; $bar++) {
                if ($bar < $count && $array[$bar] > $array[$bar + 1]) {
                    self::swapArray($array, $bar, $bar + 1);
                }
            }
        }
    }

    /**
     * @Annotation\TwigFilter()
     * @Annotation\TwigFunction()
     */
    public static function swapArray(array &$array, mixed $foo, mixed $bar): void
    {
        if ($array[$foo] === $array[$bar]) {
            return;
        }

        $tmp = $array[$foo];
        $array[$foo] = $array[$bar];
        $array[$bar] = $tmp;
    }

    /**
     * @Annotation\TwigFilter()
     */
    public static function mergeSort(array $array): array
    {
        if (1 === count($array)) {
            return $array;
        }

        $middle = (int)round(count($array) / 2);
        $left = array_slice($array, 0, $middle);
        $right = array_slice($array, $middle);

        $left = self::mergeSort($left);
        $right = self::mergeSort($right);

        return self::merge($left, $right);
    }

    private static function merge(array $left, array $right): array
    {
        $result = [];
        $i = $j = 0;

        $leftCount = count($left);
        $rightCount = count($right);

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

    /**
     * @Annotation\TwigFunction()
     */
    #[Pure]
    public static function isSortable(mixed $item, mixed $field): bool
    {
        if (is_array($item)) {
            return array_key_exists($field, $item);
        }

        if (is_object($item)) {
            return isset($item->$field) || property_exists($item, $field);
        }

        return false;
    }

    /**
     * @Annotation\TwigFilter()
     */
    public static function usort(mixed $parameter, string $order): callable
    {
        return static function ($a, $b) use ($parameter, $order): int {
            $flip = ($order === self::DESC) ? -1 : 1;

            if (($aSort = Php::getParameter($a, $parameter)) === ($bSort = Php::getParameter($b, $parameter))) {
                return 0;
            }

            if ($aSort > $bSort) {
                return $flip;
            }

            return (-1 * $flip);
        };
    }
}
