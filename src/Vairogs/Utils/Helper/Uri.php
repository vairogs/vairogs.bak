<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use CURLFile;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\HttpFoundation\Request;
use Vairogs\Extra\Constants;
use Vairogs\Utils\Twig\Attribute;
use function array_combine;
use function array_keys;
use function array_map;
use function array_merge;
use function bin2hex;
use function explode;
use function filter_var;
use function http_build_query;
use function is_array;
use function is_object;
use function ltrim;
use function parse_str;
use function parse_url;
use function preg_match;
use function preg_replace;
use function preg_replace_callback;
use function sprintf;
use function str_replace;
use function str_starts_with;
use function strpos;
use function strrpos;
use function substr;
use function urldecode;
use const FILTER_SANITIZE_URL;
use const FILTER_VALIDATE_URL;

final class Uri
{
    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function buildHttpQueryArray(array|object $input, ?string $parent = null): array
    {
        $result = [];

        foreach (Php::getArray(input: $input) as $key => $value) {
            $newKey = match ($parent) {
                null => $key,
                default => sprintf('%s[%s]', $parent, $key)
            };

            if (!$value instanceof CURLFile && (is_array(value: $value) || is_object(value: $value))) {
                /** @noinspection SlowArrayOperationsInLoopInspection */
                $result = array_merge($result, self::buildHttpQueryArray(input: $value, parent: $newKey));
            } else {
                $result[$newKey] = $value;
            }
        }

        return $result;
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function buildArrayFromObject(object $object): array
    {
        parse_str(string: self::buildHttpQueryString($object), result: $result);

        return $result;
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function buildHttpQueryString(object $object): string
    {
        return http_build_query(data: self::buildHttpQueryArray(input: $object));
    }

    #[Attribute\TwigFilter]
    public static function urlEncode(string $url): string
    {
        $urlParsed = parse_url(url: $url);

        $scheme = $urlParsed['scheme'] . '://';
        $host = $urlParsed['host'];
        $port = $urlParsed['port'] ?? null;
        $path = $urlParsed['path'] ?? null;
        $query = $urlParsed['query'] ?? null;

        if (null !== $query) {
            /** @var string $query */
            $query = '?' . http_build_query(data: self::arrayFromQueryString(query: $query));
        }

        if ($port && ':' !== $port[0]) {
            $port = ':' . $port;
        }

        return $scheme . $host . $port . $path . $query;
    }

    #[Attribute\TwigFilter]
    public static function arrayFromQueryString(string $query): array
    {
        $query = preg_replace_callback(pattern: '#(?:^|(?<=&))[^=[]+#', callback: static fn ($match) => bin2hex(string: urldecode(string: $match[0])), subject: $query);

        parse_str(string: $query, result: $values);

        return array_combine(keys: array_map(callback: 'hex2bin', array: array_keys(array: $values)), values: $values);
    }

    #[Attribute\TwigFilter]
    public static function parseHeaders(string $rawHeaders = ''): array
    {
        $headers = [];
        $headerArray = str_replace(search: "\r", replace: '', subject: $rawHeaders);
        $headerArray = explode(separator: "\n", string: $headerArray);

        foreach ($headerArray as $value) {
            $header = explode(separator: ': ', string: $value, limit: 2);

            if ($header[0] && !$header[1]) {
                $headers['status'] = $header[0];
            } elseif ($header[0] && $header[1]) {
                $headers[$header[0]] = $header[1];
            }
        }

        return $headers;
    }

    #[Attribute\TwigFunction]
    #[Pure]
    public static function isUrl(string $url): bool
    {
        /* @noinspection BypassedUrlValidationInspection */
        return false !== filter_var(value: filter_var(value: $url, filter: FILTER_SANITIZE_URL), filter: FILTER_VALIDATE_URL);
    }

    #[Attribute\TwigFilter]
    public static function parseQueryPath(string $path): bool|string
    {
        $path = '/' . ltrim(string: $path, characters: '/');
        $path = preg_replace(pattern: '#[\x00-\x1F\x7F]#', replacement: '', subject: $path);

        while (false !== $pos = strpos(haystack: $path, needle: '/../')) {
            $leftSlashNext = strrpos(haystack: substr(string: $path, offset: 0, length: $pos), needle: '/');

            if (!$leftSlashNext) {
                return false;
            }

            $path = substr(string: $path, offset: 0, length: $leftSlashNext + 1) . substr(string: $path, offset: $pos + 4);
        }

        return $path;
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function getSchema(Request $request): string
    {
        return Http::isHttps(req: $request) ? Constants\Http::SCHEMA_HTTPS : Constants\Http::SCHEMA_HTTP;
    }

    #[Attribute\TwigFunction]
    public static function isAbsolute(string $path): bool
    {
        return str_starts_with(haystack: $path, needle: '//') || preg_match(pattern: '#^[a-z-]{3,}://#i', subject: $path);
    }
}
