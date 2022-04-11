<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use Exception;
use Vairogs\Extra\Constants\Symbol;
use Vairogs\Utils\Twig\Attribute;
use function is_array;
use function is_object;
use function usort;

class SortLatvian
{
    private static int|string $field = '';

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function sortLatvian(array &$names, string|int $field, array $callback = [self::class, 'compareLatvian']): bool
    {
        self::$field = $field;
        $result = usort(array: $names, callback: $callback);
        self::$field = '';

        return $result;
    }

    public static function compareLatvianArray(array $first, array $second): int
    {
        if (null === ($firstValue = $first[self::$field] ?? null) || null === ($secondValue = $second[self::$field] ?? null)) {
            return 0;
        }

        return self::compare(first: $firstValue, second: $secondValue);
    }

    public static function compareLatvian(array|object $first, array|object $second): int
    {
        return match (true) {
            is_array(value: $first) && is_array(value: $second) => self::compareLatvianArray(first: $first, second: $second),
            is_object(value: $first) && is_object(value: $second) => self::compareLatvianObject(first: $first, second: $second),
            default => 0
        };
    }

    public static function compareLatvianObject(object $first, object $second): int
    {
        try {
            $firstValue = Php::hijackGet(object: $first, property: self::$field);
            $secondValue = Php::hijackGet(object: $second, property: self::$field);
        } catch (Exception) {
            return 0;
        }

        return self::compare(first: $firstValue, second: $secondValue);
    }

    private static function compare(string $first, string $second): int
    {
        for ($i = 0, $len = mb_strlen(string: $first); $i < $len; $i++) {
            if (($charFirst = mb_substr(string: $first, start: $i, length: 1)) === ($charSecond = mb_substr(string: $second, start: $i, length: 1))) {
                continue;
            }

            if ($i > mb_strlen(string: $second) || mb_strpos(haystack: Symbol::LV_LOWERCASE, needle: $charFirst) > mb_strpos(haystack: Symbol::LV_LOWERCASE, needle: $charSecond)) {
                return 1;
            }

            return -1;
        }

        return 0;
    }
}
