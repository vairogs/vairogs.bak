<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use JetBrains\PhpStorm\Pure;
use Vairogs\Extra\Constants\Definition;
use Vairogs\Utils\Twig\Attribute;
use function array_key_exists;
use function base64_encode;
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
use function str_replace;
use function strip_tags;
use function strpbrk;
use function strrev;
use function substr;

final class Text
{
    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function cleanText(string $text): string
    {
        return html_entity_decode(string: self::oneSpace(text: str_replace(search: ' ?', replace: '', subject: mb_convert_encoding(string: strip_tags(string: $text), to_encoding: Definition::UTF8, from_encoding: Definition::UTF8))));
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function oneSpace(string $text): string
    {
        return preg_replace(pattern: '#\s+#S', replacement: ' ', subject: $text);
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function stripSpace(string $text): string
    {
        return preg_replace(pattern: '#\s+#', replacement: '', subject: $text);
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function truncateSafe(string $text, int $length, string $append = '...'): string
    {
        $result = mb_substr(string: $text, start: 0, length: $length);
        $lastSpace = mb_strrpos(haystack: $result, needle: ' ');

        if (false !== $lastSpace && $text !== $result) {
            $result = mb_substr(string: $result, start: 0, length: $lastSpace);
        }

        if ($text !== $result) {
            $result .= $append;
        }

        return $result;
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function limitChars(string $text, int $length = 100, string $append = '...'): string
    {
        if ($length >= mb_strlen(string: $text)) {
            return $text;
        }

        return rtrim(string: mb_substr(string: $text, start: 0, length: $length)) . $append;
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function limitWords(string $text, int $limit = 100, string $append = '...'): string
    {
        preg_match(pattern: '/^\s*+(?:\S++\s*+){1,' . $limit . '}/u', subject: $text, matches: $matches);
        if (!array_key_exists(key: 0, array: $matches) || mb_strlen(string: $text) === mb_strlen(string: $matches[0])) {
            return $text;
        }

        return rtrim(string: $matches[0]) . $append;
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    #[Pure]
    public static function containsAny(string $haystack, string $needle): bool
    {
        return false !== strpbrk(string: $haystack, characters: $needle);
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    #[Pure]
    public static function reverse(string $text): string
    {
        return iconv(from_encoding: 'UTF-32LE', to_encoding: Definition::UTF8, string: strrev(string: iconv(from_encoding: Definition::UTF8, to_encoding: 'UTF-32BE', string: $text)));
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function keepNumeric(string $text): string
    {
        return preg_replace(pattern: '#\D#', replacement: '', subject: $text);
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function keepAscii(string $text): string
    {
        return preg_replace(pattern: '#[[:^ascii:]]#', replacement: '', subject: $text);
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function getLastPart(string $text, string $delimiter): string
    {
        $idx = strrpos(haystack: $text, needle: $delimiter);

        return false === $idx ? $text : substr(string: $text, offset: $idx + 1);
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function getNormalizedValue(string $value): string|int|float
    {
        if (is_numeric(value: $value)) {
            return str_contains(haystack: (string) $value, needle: '.') ? (float) $value : (int) $value;
        }

        return $value;
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function getHash(string $text, int $bits = 256): string
    {
        $hash = substr(string: hash(algo: 'sha' . $bits, data: $text, binary: true), offset: 0, length: (int) round(num: $bits / 16));

        return strtr(string: rtrim(string: base64_encode(string: $hash), characters: '='), from: '+/', to: '-_');
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function htmlEntityDecode(string $text): array|string|null
    {
        return preg_replace(pattern: '#\R+#', replacement: '', subject: html_entity_decode(string: $text));
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function detectLanguage(string $body): string
    {
        return match (true) {
            str_starts_with(haystack: $body, needle: '<?xml') => 'xml',
            str_starts_with(haystack: $body, needle: '{'), str_starts_with(haystack: $body, needle: '[') => 'json',
            default => 'plain',
        };
    }
}
