<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Helper;

use CURLFile;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\HttpFoundation\Request;
use Vairogs\Component\Utils\Twig\Annotation;
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

class Uri
{
    #[Annotation\TwigFunction]
    #[Annotation\TwigFilter]
    public static function buildHttpQueryArray(array|object $data, ?string $parent = null): array
    {
        $result = [];

        foreach (Php::getArray($data) as $key => $value) {
            $newKey = $parent ? sprintf('%s[%s]', $parent, $key) : $key;

            if (!$value instanceof CURLFile && (is_array($value) || is_object($value))) {
                /** @noinspection SlowArrayOperationsInLoopInspection */
                $result = array_merge($result, self::buildHttpQueryArray($value, $newKey));
            } else {
                $result[$newKey] = $value;
            }
        }

        return $result;
    }

    #[Annotation\TwigFilter]
    public static function urlEncode(string $url): string
    {
        $urlParsed = parse_url($url);

        $scheme = $urlParsed['scheme'] . '://';
        $host = $urlParsed['host'];
        $port = $urlParsed['port'] ?? null;
        $path = $urlParsed['path'] ?? null;
        $query = $urlParsed['query'] ?? null;

        if (null !== $query) {
            /** @var string $query */
            $query = '?' . http_build_query(self::arrayFromQueryString($query));
        }

        if ($port && ':' !== $port[0]) {
            $port = ':' . $port;
        }

        return $scheme . $host . $port . $path . $query;
    }

    #[Annotation\TwigFilter]
    public static function arrayFromQueryString(string $query): array
    {
        $query = preg_replace_callback('#(?:^|(?<=&))[^=[]+#', static fn($match) => bin2hex(urldecode($match[0])), $query);

        parse_str($query, $values);

        return array_combine(array_map('hex2bin', array_keys($values)), $values);
    }

    #[Annotation\TwigFilter]
    public static function parseHeaders(string $rawHeaders = ''): array
    {
        $headers = [];
        $headerArray = str_replace("\r", "", $rawHeaders);
        $headerArray = explode("\n", $headerArray);

        foreach ($headerArray as $value) {
            $header = explode(": ", $value, 2);

            if ($header[0] && !$header[1]) {
                $headers['status'] = $header[0];
            } elseif ($header[0] && $header[1]) {
                $headers[$header[0]] = $header[1];
            }
        }

        return $headers;
    }

    #[Annotation\TwigFunction]
    #[Pure]
    public static function isUrl(string $url): bool
    {
        /** @noinspection BypassedUrlValidationInspection */
        return false !== filter_var(filter_var($url, FILTER_SANITIZE_URL), FILTER_VALIDATE_URL);
    }

    #[Annotation\TwigFilter]
    public static function parseQueryPath(string $path): bool|string
    {
        $path = '/' . ltrim($path, '/');
        $path = preg_replace('#[\x00-\x1F\x7F]#', '', $path);

        while (false !== $pos = strpos($path, '/../')) {
            $leftSlashNext = strrpos(substr($path, 0, $pos), '/');

            if (!$leftSlashNext) {
                return false;
            }

            $path = substr($path, 0, $leftSlashNext + 1) . substr($path, $pos + 4);
        }

        return $path;
    }

    #[Annotation\TwigFunction]
    #[Annotation\TwigFilter]
    public static function getSchema(Request $request): string
    {
        return Http::isHttps($request) ? 'https://' : 'http://';
    }

    #[Annotation\TwigFunction]
    public static function isAbsolute(string $path): bool
    {
        return str_starts_with($path, '//') || preg_match('#^[a-z-]{3,}://#i', $path);
    }
}
