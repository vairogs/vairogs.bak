<?php declare(strict_types = 1);

namespace Vairogs\Functions;

use Exception;
use Vairogs\Functions\Constants\Symbol;

use function is_array;
use function is_object;
use function usort;

final class SortLatvian
{
    private static string $field = '';

    public function sortLatvian(array &$names, string|int $field, array $callback = [self::class, 'compareLatvian']): bool
    {
        self::$field = (string) $field;
        $result = usort(array: $names, callback: $callback);
        self::$field = '';

        return $result;
    }

    public function compareLatvianArray(array $first, array $second): int
    {
        if (null === ($firstValue = $first[self::$field] ?? null) || null === ($secondValue = $second[self::$field] ?? null)) {
            return 0;
        }

        return $this->compare(first: $firstValue, second: $secondValue);
    }

    public function compareLatvian(array|object $first, array|object $second): int
    {
        return match (true) {
            is_array(value: $first) && is_array(value: $second) => $this->compareLatvianArray(first: $first, second: $second),
            is_object(value: $first) && is_object(value: $second) => $this->compareLatvianObject(first: $first, second: $second),
            default => 0,
        };
    }

    public function compareLatvianObject(object $first, object $second): int
    {
        try {
            $firstValue = (new Closure())->hijackGet(object: $first, property: self::$field);
            $secondValue = (new Closure())->hijackGet(object: $second, property: self::$field);
        } catch (Exception) {
            return 0;
        }

        return null !== $firstValue && null !== $secondValue ? $this->compare(first: $firstValue, second: $secondValue) : 0;
    }

    private function compare(string $first, string $second): int
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
