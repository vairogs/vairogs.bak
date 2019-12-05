<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Helper;

use function array_slice;
use function count;

class Sort
{
    /**
     * @param $foo
     * @param $bar
     */
    public static function swap(&$foo, &$bar): void
    {
        if ($foo === $bar) {
            return;
        }

        $tmp = $foo;
        $foo = $bar;
        $bar = $tmp;
    }

    /**
     * @param array $array
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
     * @param array $array
     * @param $foo
     * @param $bar
     */
    public static function swapArray(array &$array, $foo, $bar): void
    {
        if ($array[$foo] === $array[$bar]) {
            return;
        }

        $tmp = $array[$foo];
        $array[$foo] = $array[$bar];
        $array[$bar] = $tmp;
    }

    /**
     * @param $array
     *
     * @return array
     */
    public static function mergeSort($array): array
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

    /**
     * @param $left
     * @param $right
     *
     * @return array
     */
    private static function merge($left, $right): array
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
}
