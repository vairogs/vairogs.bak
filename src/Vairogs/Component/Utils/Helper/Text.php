<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Helper;

use function array_key_exists;
use function filter_var;
use function html_entity_decode;
use function iconv;
use function is_numeric;
use function lcfirst;
use function mb_convert_encoding;
use function mb_strlen;
use function mb_strpos;
use function mb_strrpos;
use function mb_strtolower;
use function mb_substr;
use function preg_match;
use function preg_replace;
use function rtrim;
use function str_contains;
use function str_ends_with;
use function str_pad;
use function str_replace;
use function strip_tags;
use function strrev;
use function strtolower;
use function ucwords;
use function usort;
use const false;
use const FILTER_FLAG_ALLOW_FRACTION;
use const FILTER_SANITIZE_NUMBER_FLOAT;
use const STR_PAD_LEFT;
use const true;

class Text
{
    // @formatter:off
    public const CYRMAP = [
        'е', 'ё', 'ж', 'х', 'ц', 'ч', 'ш', 'щ', 'ю', 'я',
        'Е', 'Ё', 'Ж', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ю', 'Я',
        'а', 'б', 'в', 'г', 'д', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'ъ', 'ы', 'ь', 'э',
        'А', 'Б', 'В', 'Г', 'Д', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Ъ', 'Ы', 'Ь', 'Э'
    ];
    public const LATMAP = [
        'ye', 'ye', 'zh', 'kh', 'ts', 'ch', 'sh', 'shch', 'yu', 'ya',
        'Ye', 'Ye', 'Zh', 'Kh', 'Ts', 'Ch', 'Sh', 'Shch', 'Yu', 'Ya',
        'a', 'b', 'v', 'g', 'd', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'ʺ', 'y', '–', 'e',
        'A', 'B', 'V', 'G', 'D', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'ʺ', 'Y', '–', 'E'
    ];
    // @formatter:on

    /**
     * @param string $string
     * @param string $separator
     *
     * @return string
     */
    public static function fromCamelCase(string $string, string $separator = '_'): string
    {
        return strtolower(preg_replace('/(?!^)[[:upper:]]+/', $separator . '$0', $string));
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public static function toSnakeCase(string $string): string
    {
        $string = preg_replace('#([A-Z\d]+)([A-Z][a-z])#', '\1_\2', self::toCamelCase($string));
        $string = preg_replace('#([a-z\d])([A-Z])#', '\1_\2', $string);

        return strtolower(str_replace('-', '_', $string));
    }

    /**
     * @param string $string
     * @param bool $lowFirst
     *
     * @return string
     * @noinspection StringNormalizationInspection
     */
    public static function toCamelCase(string $string, bool $lowFirst = true): string
    {
        if ($lowFirst) {
            return preg_replace('~\s+~', '', lcfirst(ucwords(strtolower(str_replace('_', ' ', $string)))));
        }

        return preg_replace('~\s+~', '', ucwords(strtolower(str_replace('_', ' ', $string))));
    }

    /**
     * @param string $text
     *
     * @return string
     */
    public static function cleanText(string $text): string
    {
        return html_entity_decode(self::oneSpace(str_replace(' ?', '', mb_convert_encoding(strip_tags($text), 'UTF-8', 'UTF-8'))));
    }

    /**
     * @param string $text
     *
     * @return string
     */
    public static function oneSpace(string $text): string
    {
        return preg_replace('/\s+/S', ' ', $text);
    }

    /**
     * @param string $text
     *
     * @return string
     */
    public static function translitCyrToLat(string $text): string
    {
        return str_replace(self::CYRMAP, self::LATMAP, $text);
    }

    /**
     * @param string $text
     *
     * @return string
     */
    public static function translitLatToCyr(string $text): string
    {
        return str_replace(self::LATMAP, self::CYRMAP, $text);
    }

    /**
     * @param array $names
     *
     * @return bool
     */
    public static function sortLatvian(array $names): bool
    {
        return usort($names, [
            __CLASS__,
            'compareLatvian',
        ]);
    }

    /**
     * @param $a
     * @param $b
     * @param $field
     * @param string $type
     *
     * @return int
     */
    public static function compareLatvian($a, $b, $field, $type = 'array'): int
    {
        $alphabet = 'aābcčdeēfgģhiījkķlļmnņoprsštuūvzž';
        if ('array' === $type) {
            $a = mb_strtolower($a[$field]);
            $b = mb_strtolower($b[$field]);
        } else {
            $a = mb_strtolower($a->$field());
            $b = mb_strtolower($b->$field());
        }
        $len = mb_strlen($a);

        for ($i = 0; $i < $len; $i++) {
            if (mb_substr($a, $i, 1) === mb_substr($b, $i, 1)) {
                continue;
            }
            if ($i > mb_strlen($b)) {
                return 1;
            }
            if (mb_strpos($alphabet, mb_substr($a, $i, 1)) > mb_strpos($alphabet, mb_substr($b, $i, 1))) {
                return 1;
            }

            return -1;
        }

        return 0;
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public static function stripSpace(string $string): string
    {
        return preg_replace('/\s+/', '', $string);
    }

    /**
     * @param int $number
     * @param int $length
     *
     * @return string
     */
    public static function zero(int $number, int $length): string
    {
        return str_pad($number, $length, '0', STR_PAD_LEFT);
    }

    /**
     * @param string $string
     * @param int $length
     * @param string $append
     *
     * @return string
     */
    public static function truncateSafe(string $string, int $length, string $append = '...'): string
    {
        $result = mb_substr($string, 0, $length);
        $lastSpace = mb_strrpos($result, ' ');

        if (false !== $lastSpace && $string !== $result) {
            $result = mb_substr($result, 0, $lastSpace);
        }

        if ($string !== $result) {
            $result .= $append;
        }

        return $result;
    }

    /**
     * @param string $string
     * @param int $limit
     * @param string $append
     *
     * @return string
     */
    public static function limitChars(string $string, int $limit = 100, string $append = '...'): string
    {
        if ($limit >= mb_strlen($string)) {
            return $string;
        }

        return rtrim(mb_substr($string, 0, $limit)) . $append;
    }

    /**
     * @param string $string
     * @param int $limit
     * @param string $append
     *
     * @return string
     */
    public static function limitWords(string $string, int $limit = 100, string $append = '...'): string
    {
        preg_match('/^\s*+(?:\S++\s*+){1,' . $limit . '}/u', $string, $matches);
        if (!array_key_exists(0, $matches) || mb_strlen($string) === mb_strlen($matches[0])) {
            return $string;
        }

        return rtrim($matches[0]) . $append;
    }

    /**
     * @param string $haystack
     * @param string $needle
     * @param bool $caseSensitive
     *
     * @return bool
     */
    public static function starts(string $haystack, string $needle, bool $caseSensitive = false): bool
    {
        $function = 'mb_stripos';
        if (true === $caseSensitive) {
            $function = 'mb_strpos';
        }

        return '' === $needle || 0 === $function($haystack, $needle);
    }

    /**
     * @param string $haystack
     * @param string $needle
     * @param bool $caseSensitive
     *
     * @return bool
     */
    public static function ends(string $haystack, string $needle, bool $caseSensitive = false): bool
    {
        if (false === $caseSensitive) {
            $haystack = mb_strtolower($haystack);
            $needle = mb_strtolower($needle);
        }

        return '' === $needle || str_ends_with($haystack, $needle);
    }

    /**
     * @param string $haystack
     * @param string $needle
     *
     * @return bool
     */
    public static function contains(string $haystack, string $needle): bool
    {
        return str_contains($haystack, $needle);
    }

    /**
     * @param string $haystack
     * @param string $needle
     *
     * @return bool
     */
    public static function containsAny(string $haystack, string $needle): bool
    {
        return false !== strpbrk($haystack, $needle);
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public static function reverse(string $string): string
    {
        return iconv('UTF-32LE', 'UTF-8', strrev(iconv('UTF-8', 'UTF-32BE', $string)));
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public static function keepNumeric(string $string): string
    {
        return preg_replace('~\D~', '', $string);
    }

    /**
     * @param $value
     *
     * @return int|string
     */
    public static function getNumeric($value)
    {
        if (is_numeric($value)) {
            return $value + 0;
        }

        return $value;
    }

    /**
     * @param string $string
     *
     * @return float
     */
    public static function sanitizeFloat(string $string): float
    {
        return (float)filter_var($string, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }
}
