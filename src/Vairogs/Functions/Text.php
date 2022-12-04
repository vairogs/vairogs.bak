<?php declare(strict_types = 1);

namespace Vairogs\Functions;

use JetBrains\PhpStorm\Pure;
use Vairogs\Functions\Constants\Symbol;

use function array_key_exists;
use function array_reverse;
use function html_entity_decode;
use function implode;
use function is_numeric;
use function max;
use function preg_match;
use function preg_replace;
use function rtrim;
use function str_replace;
use function strip_tags;
use function strlen;
use function strpbrk;
use function strrpos;
use function substr;

final class Text
{
    public function cleanText(string $text): string
    {
        return $this->htmlEntityDecode(text: $this->oneSpace(text: str_replace(search: ' ?', replace: '', subject: mb_convert_encoding(string: strip_tags(string: $text), to_encoding: Symbol::UTF8, from_encoding: Symbol::UTF8))));
    }

    public function oneSpace(string $text): string
    {
        return (string) preg_replace(pattern: '#\s+#S', replacement: ' ', subject: $text);
    }

    public function stripSpace(string $text): string
    {
        return (string) preg_replace(pattern: '#\s+#', replacement: '', subject: $text);
    }

    public function truncateSafe(string $text, int $length, string $append = '...'): string
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

    public function limitChars(string $text, int $length = 100, string $append = '...'): string
    {
        if ($length >= mb_strlen(string: $text)) {
            return $text;
        }

        return rtrim(string: mb_substr(string: $text, start: 0, length: $length)) . $append;
    }

    public function limitWords(string $text, int $limit = 100, string $append = '...'): string
    {
        preg_match(pattern: '/^\s*+(?:\S++\s*+){1,' . $limit . '}/u', subject: $text, matches: $matches);
        if (!array_key_exists(key: 0, array: $matches) || mb_strlen(string: $text) === mb_strlen(string: $matches[0])) {
            return $text;
        }

        return rtrim(string: $matches[0]) . $append;
    }

    #[Pure]
    public function contains(string $haystack, string $needle): bool
    {
        return false !== strpbrk(string: $haystack, characters: $needle);
    }

    public function containsAny(string $haystack, array $needles = []): bool
    {
        foreach ($needles as $needle) {
            if (str_contains(haystack: $haystack, needle: (string) $needle)) {
                return true;
            }
        }

        return false;
    }

    public function reverseUTF8(string $text): string
    {
        /* @noinspection PhpRedundantOptionalArgumentInspection */
        return implode(separator: '', array: array_reverse(array: mb_str_split(string: $text, encoding: Symbol::UTF8)));
    }

    public function keepNumeric(string $text): string
    {
        return (string) preg_replace(pattern: '#\D#', replacement: '', subject: $text);
    }

    public function getLastPart(string $text, string $delimiter): string
    {
        return false === ($idx = strrpos(haystack: $text, needle: $delimiter)) ? $text : substr(string: $text, offset: $idx + 1);
    }

    public function getNormalizedValue(string $value, string $delimiter = '.'): string|int|float
    {
        if (is_numeric(value: $value)) {
            return str_contains(haystack: (string) $value, needle: $delimiter) ? (float) $value : (int) $value;
        }

        return $value;
    }

    public function htmlEntityDecode(string $text): string
    {
        return preg_replace(pattern: '#\R+#', replacement: '', subject: html_entity_decode(string: $text));
    }

    public function sanitize(string $text): string
    {
        return str_replace(search: ["'", '"'], replace: ['&#39;', '&#34;'], subject: (string) preg_replace(pattern: '/\x00|<[^>]*>?/', replacement: '', subject: $text));
    }

    public function longestSubstrLength(string $string): int
    {
        $result = $start = 0;
        $chars = [];

        for ($i = 0, $len = strlen(string: $string); $i < $len; $i++) {
            if (isset($chars[$string[$i]])) {
                $start = max($start, $chars[$string[$i]] + 1);
            }

            $result = max($result, $i - $start + 1);
            $chars[$string[$i]] = $i;
        }

        return $result;
    }
}
