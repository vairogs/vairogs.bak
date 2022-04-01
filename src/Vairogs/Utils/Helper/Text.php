<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use JetBrains\PhpStorm\Pure;
use Vairogs\Utils\Twig\Attribute;
use function array_key_exists;
use function base64_encode;
use function filter_var;
use function hash;
use function html_entity_decode;
use function iconv;
use function is_numeric;
use function mb_convert_encoding;
use function mb_strlen;
use function mb_strrpos;
use function mb_substr;
use function preg_match;
use function preg_replace;
use function round;
use function rtrim;
use function str_pad;
use function str_replace;
use function strip_tags;
use function strpbrk;
use function strrev;
use function strtolower;
use function substr;
use function ucwords;

final class Text
{
    final public const UTF8 = 'UTF-8';

    #[Attribute\TwigFilter]
    public static function fromCamelCase(string $string, string $separator = '_'): string
    {
        return strtolower(string: preg_replace(pattern: '#(?!^)[[:upper:]]+#', replacement: $separator . '$0', subject: $string));
    }

    #[Attribute\TwigFilter]
    public static function toSnakeCase(string $string, bool $skipCamel = false): string
    {
        $string = preg_replace(pattern: [
            '#([A-Z\d]+)([A-Z][a-z])#',
            '#([a-z\d])([A-Z])#',
        ], replacement: '\1_\2', subject: $skipCamel ? $string : self::toCamelCase(string: $string));

        return strtolower(string: str_replace(search: '-', replace: '_', subject: $string));
    }

    #[Attribute\TwigFilter]
    public static function toCamelCase(string $string, bool $lowFirst = true): string
    {
        $function = true === $lowFirst ? 'lcfirst' : 'ucfirst';

        return preg_replace(pattern: '#\s+#', replacement: '', subject: $function(string: ucwords(string: strtolower(string: str_replace(search: '_', replace: ' ', subject: $string)))));
    }

    #[Attribute\TwigFilter]
    public static function cleanText(string $text): string
    {
        return html_entity_decode(string: self::oneSpace(text: str_replace(search: ' ?', replace: '', subject: mb_convert_encoding(string: strip_tags(string: $text), to_encoding: self::UTF8, from_encoding: self::UTF8))));
    }

    #[Attribute\TwigFilter]
    public static function oneSpace(string $text): string
    {
        return preg_replace(pattern: '#\s+#S', replacement: ' ', subject: $text);
    }

    #[Attribute\TwigFilter]
    public static function stripSpace(string $string): string
    {
        return preg_replace(pattern: '#\s+#', replacement: '', subject: $string);
    }

    #[Attribute\TwigFilter]
    public static function truncateSafe(string $string, int $length, string $append = '...'): string
    {
        $result = mb_substr(string: $string, start: 0, length: $length);
        $lastSpace = mb_strrpos(haystack: $result, needle: ' ');

        if (false !== $lastSpace && $string !== $result) {
            $result = mb_substr(string: $result, start: 0, length: $lastSpace);
        }

        if ($string !== $result) {
            $result .= $append;
        }

        return $result;
    }

    #[Attribute\TwigFilter]
    public static function limitChars(string $string, int $length = 100, string $append = '...'): string
    {
        if ($length >= mb_strlen(string: $string)) {
            return $string;
        }

        return rtrim(string: mb_substr(string: $string, start: 0, length: $length)) . $append;
    }

    #[Attribute\TwigFilter]
    public static function limitWords(string $string, int $limit = 100, string $append = '...'): string
    {
        preg_match(pattern: '/^\s*+(?:\S++\s*+){1,' . $limit . '}/u', subject: $string, matches: $matches);
        if (!array_key_exists(key: 0, array: $matches) || mb_strlen(string: $string) === mb_strlen(string: $matches[0])) {
            return $string;
        }

        return rtrim(string: $matches[0]) . $append;
    }

    #[Attribute\TwigFunction]
    #[Pure]
    public static function containsAny(string $haystack, string $needle): bool
    {
        return false !== strpbrk(string: $haystack, characters: $needle);
    }

    #[Attribute\TwigFilter]
    #[Pure]
    public static function reverse(string $string): string
    {
        return iconv(from_encoding: 'UTF-32LE', to_encoding: self::UTF8, string: strrev(string: iconv(from_encoding: self::UTF8, to_encoding: 'UTF-32BE', string: $string)));
    }

    #[Attribute\TwigFilter]
    public static function keepNumeric(string $string): string
    {
        return preg_replace(pattern: '#\D#', replacement: '', subject: $string);
    }

    #[Attribute\TwigFilter]
    public static function keepAscii(string $string): string
    {
        return preg_replace(pattern: '#[[:^ascii:]]#', replacement: '', subject: $string);
    }

    #[Attribute\TwigFilter]
    #[Pure]
    public static function sanitizeFloat(string $string): float
    {
        return (float) filter_var(value: $string, filter: FILTER_SANITIZE_NUMBER_FLOAT, options: FILTER_FLAG_ALLOW_FRACTION);
    }

    #[Attribute\TwigFilter]
    public static function getLastPart(string $string, string $delimiter): string
    {
        $idx = strrpos(haystack: $string, needle: $delimiter);

        return false === $idx ? $string : substr(string: $string, offset: $idx + 1);
    }

    public static function getNormalizedValue(string $value): string|int|float
    {
        if (is_numeric(value: $value)) {
            return str_contains(haystack: (string) $value, needle: '.') ? (float) $value : (int) $value;
        }

        return $value;
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function getHash(string $hashable, int $bit = 256): string
    {
        $hash = substr(string: hash(algo: 'sha' . $bit, data: $hashable, binary: true), offset: 0, length: (int) round(num: $bit / 16));

        return strtr(string: rtrim(string: base64_encode(string: $hash), characters: '='), from: '+/', to: '-_');
    }

    #[Attribute\TwigFilter]
    #[Pure]
    public static function pad(string $input, int $length, string $padding, int $type = STR_PAD_LEFT): string
    {
        return str_pad(string: $input, length: $length, pad_string: $padding, pad_type: $type);
    }
}
