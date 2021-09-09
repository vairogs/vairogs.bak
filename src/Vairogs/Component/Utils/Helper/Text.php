<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Helper;

use JetBrains\PhpStorm\Pure;
use Vairogs\Component\Utils\Twig\Annotation;
use function array_key_exists;
use function filter_var;
use function html_entity_decode;
use function iconv;
use function is_numeric;
use function mb_convert_encoding;
use function mb_strlen;
use function mb_strrpos;
use function mb_substr;
use function preg_match;
use function preg_replace;
use function rtrim;
use function str_pad;
use function str_replace;
use function strip_tags;
use function strpbrk;
use function strrev;
use function strtolower;
use function ucwords;

class Text
{
    public const UTF8 = 'UTF-8';

    #[Annotation\TwigFilter]
    public static function fromCamelCase(string $string, string $separator = '_'): string
    {
        return strtolower(preg_replace('#(?!^)[[:upper:]]+#', $separator . '$0', $string));
    }

    #[Annotation\TwigFilter]
    public static function toSnakeCase(string $string): string
    {
        $string = preg_replace([
            '#([A-Z\d]+)([A-Z][a-z])#',
            '#([a-z\d])([A-Z])#',
        ], '\1_\2', self::toCamelCase($string));

        return strtolower(str_replace('-', '_', $string));
    }

    #[Annotation\TwigFilter]
    public static function toCamelCase(string $string, bool $lowFirst = true): string
    {
        $function = true === $lowFirst ? 'lcfirst' : 'ucfirst';

        return preg_replace('#\s+#', '', $function(ucwords(strtolower(str_replace('_', ' ', $string)))));
    }

    #[Annotation\TwigFilter]
    public static function cleanText(string $text): string
    {
        return html_entity_decode(self::oneSpace(str_replace(' ?', '', mb_convert_encoding(strip_tags($text), self::UTF8, self::UTF8))));
    }

    #[Annotation\TwigFilter]
    public static function oneSpace(string $text): string
    {
        return preg_replace('#\s+#S', ' ', $text);
    }

    #[Annotation\TwigFilter]
    public static function stripSpace(string $string): string
    {
        return preg_replace('#\s+#', '', $string);
    }

    #[Annotation\TwigFilter]
    #[Pure]
    public static function zero(string $input, int $length): string
    {
        return str_pad($input, $length, '0', STR_PAD_LEFT);
    }

    #[Annotation\TwigFilter]
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

    #[Annotation\TwigFilter]
    public static function limitChars(string $string, int $limit = 100, string $append = '...'): string
    {
        if ($limit >= mb_strlen($string)) {
            return $string;
        }

        return rtrim(mb_substr($string, 0, $limit)) . $append;
    }

    #[Annotation\TwigFilter]
    public static function limitWords(string $string, int $limit = 100, string $append = '...'): string
    {
        preg_match('/^\s*+(?:\S++\s*+){1,' . $limit . '}/u', $string, $matches);
        if (!array_key_exists(0, $matches) || mb_strlen($string) === mb_strlen($matches[0])) {
            return $string;
        }

        return rtrim($matches[0]) . $append;
    }

    #[Annotation\TwigFunction]
    #[Pure]
    public static function containsAny(string $haystack, string $needle): bool
    {
        return false !== strpbrk($haystack, $needle);
    }

    #[Annotation\TwigFilter]
    #[Pure]
    public static function reverse(string $string): string
    {
        return iconv('UTF-32LE', self::UTF8, strrev(iconv(self::UTF8, 'UTF-32BE', $string)));
    }

    #[Annotation\TwigFilter]
    public static function keepNumeric(string $string): string
    {
        return preg_replace('#\D#', '', $string);
    }

    #[Annotation\TwigFilter]
    #[Pure]
    public static function sanitizeFloat(string $string): float
    {
        return (float)filter_var($string, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }

    #[Annotation\TwigFilter]
    public static function getLastPart(string $string, string $delimiter): string
    {
        $idx = strrpos($string, $delimiter);

        return $idx === false ? $string : substr($string, $idx + 1);
    }

    public static function getNormalizedValue(string $value): string|int|float
    {
        if (is_numeric($value)) {
            return str_contains((string)$value, '.') ? (float)$value : (int)$value;
        }

        return $value;
    }
}
