<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Helper;

use Doctrine\Common\Collections\Collection;
use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use Vairogs\Component\Utils\Twig\Annotation;
use function array_key_exists;
use function array_slice;
use function count;
use function current;
use function is_array;
use function is_object;
use function property_exists;
use function round;
use function strtoupper;
use function usort;

class Sort
{
    public const ASC = 'ASC';
    public const DESC = 'DESC';
    public const ALPHABET = 'aābcčdeēfgģhiījkķlļmnņoprsštuūvzž';

    private static string $field = '';

    #[Annotation\TwigFunction]
    public static function swap(mixed &$foo, mixed &$bar): void
    {
        if ($foo === $bar) {
            return;
        }

        $tmp = $foo;
        $foo = $bar;
        $bar = $tmp;
    }

    #[Annotation\TwigFilter]
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

    #[Annotation\TwigFunction]
    #[Annotation\TwigFilter]
    public static function swapArray(array &$array, mixed $foo, mixed $bar): void
    {
        if ($array[$foo] === $array[$bar]) {
            return;
        }

        $tmp = $array[$foo];
        $array[$foo] = $array[$bar];
        $array[$bar] = $tmp;
    }

    #[Annotation\TwigFilter]
    public static function mergeSort(array $array): array
    {
        if (1 === count($array)) {
            return $array;
        }

        $middle = (int) round(count($array) / 2);
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

    #[Annotation\TwigFilter]
    public static function sort(mixed $data, ?string $parameter = null, string $order = self::ASC): array
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
            throw new InvalidArgumentException("Sorting parameter doesn't exist in sortable variable");
        }

        usort($data, self::usort($parameter, strtoupper($order)));

        return $data;
    }

    #[Annotation\TwigFunction]
    #[Pure]
    public static function isSortable(mixed $item, int|string $field): bool
    {
        if (is_array($item)) {
            return array_key_exists($field, $item);
        }

        if (is_object($item)) {
            return isset($item->$field) || property_exists($item, $field);
        }

        return false;
    }

    #[Annotation\TwigFilter]
    public static function usort(string $parameter, string $order): callable
    {
        return static function (array|object $a, array|object $b) use ($parameter, $order): int {
            $flip = (self::DESC === $order) ? -1 : 1;

            if (($aSort = Php::getParameter($a, $parameter)) === ($bSort = Php::getParameter($b, $parameter))) {
                return 0;
            }

            if ($aSort > $bSort) {
                return $flip;
            }

            return -1 * $flip;
        };
    }

    #[Annotation\TwigFilter]
    public static function sortLatvian(array &$names, string $field): bool
    {
        self::$field = $field;
        $result = usort($names, [
            self::class,
            'compareLatvian',
        ]);
        self::$field = '';

        return $result;
    }

    private static function compareLatvian(array|object $a, array|object $b): int
    {
        $a = mb_strtolower(Php::getParameter($a, self::$field));
        $b = mb_strtolower(Php::getParameter($b, self::$field));

        $len = mb_strlen($a);

        for ($i = 0; $i < $len; $i++) {
            if (mb_substr($a, $i, 1) === mb_substr($b, $i, 1)) {
                continue;
            }

            if ($i > mb_strlen($b) || mb_strpos(self::ALPHABET, mb_substr($a, $i, 1)) > mb_strpos(self::ALPHABET, mb_substr($b, $i, 1))) {
                return 1;
            }

            return -1;
        }

        return 0;
    }
}
