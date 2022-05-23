<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use JetBrains\PhpStorm\Pure;
use Vairogs\Extra\Constants\Symbol;
use Vairogs\Twig\Attribute;
use function array_key_exists;
use function array_reverse;
use function html_entity_decode;
use function implode;
use function is_numeric;
use function mb_convert_encoding;
use function mb_strlen;
use function mb_strrpos;
use function mb_substr;
use function preg_match;
use function preg_replace;
use function rtrim;
use function str_contains;
use function str_replace;
use function strip_tags;
use function strpbrk;
use function strrpos;
use function substr;

final class Text
{
    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function cleanText(string $text): string
    {
        return $this->htmlEntityDecode(text: $this->oneSpace(text: str_replace(search: ' ?', replace: '', subject: mb_convert_encoding(string: strip_tags(string: $text), to_encoding: Symbol::UTF8, from_encoding: Symbol::UTF8))));
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function oneSpace(string $text): string
    {
        return $this->replacePattern(pattern: '#\s+#S', replacement: ' ', text: $text);
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function stripSpace(string $text): string
    {
        return $this->replacePattern(pattern: '#\s+#', text: $text);
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
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

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function limitChars(string $text, int $length = 100, string $append = '...'): string
    {
        if ($length >= mb_strlen(string: $text)) {
            return $text;
        }

        return rtrim(string: mb_substr(string: $text, start: 0, length: $length)) . $append;
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function limitWords(string $text, int $limit = 100, string $append = '...'): string
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
    public function contains(string $haystack, string $needle): bool
    {
        return false !== strpbrk(string: $haystack, characters: $needle);
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function containsAny(string $haystack, array $needles = []): bool
    {
        foreach ($needles as $needle) {
            if (str_contains(haystack: $haystack, needle: $needle)) {
                return true;
            }
        }

        return false;
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function reverseUTF8(string $text): string
    {
        return implode(separator: '', array: array_reverse(array: mb_str_split(string: $text, encoding: Symbol::UTF8)));
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function keepNumeric(string $text): string
    {
        return $this->replacePattern(pattern: '#\D#', text: $text);
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function replacePattern(string $pattern, string $replacement = '', string $text = ''): string
    {
        return preg_replace(pattern: $pattern, replacement: $replacement, subject: $text);
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function getLastPart(string $text, string $delimiter): string
    {
        return false === ($idx = strrpos(haystack: $text, needle: $delimiter)) ? $text : substr(string: $text, offset: $idx + 1);
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function getNormalizedValue(string $value, string $delimiter = '.'): string|int|float
    {
        if (is_numeric(value: $value)) {
            return str_contains(haystack: (string) $value, needle: $delimiter) ? (float) $value : (int) $value;
        }

        return $value;
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function htmlEntityDecode(string $text): array|string|null
    {
        return $this->replacePattern(pattern: '#\R+#', text: html_entity_decode(string: $text));
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function sanitize(string $text): string
    {
        /* @noinspection PhpDeprecationInspection */
        return preg_replace(pattern: ['~[\r\n]+~', '#\s+#S'], replacement: ' ', subject: filter_var(value: $text, filter: FILTER_SANITIZE_STRING));
    }
}
