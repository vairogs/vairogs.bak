<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Helper;

use CURLFile;
use JetBrains\PhpStorm\Pure;
use Vairogs\Component\Utils\Annotation;
use function array_combine;
use function array_keys;
use function array_map;
use function array_merge;
use function bin2hex;
use function explode;
use function filter_var;
use function function_exists;
use function get_object_vars;
use function http_build_query;
use function http_parse_headers;
use function is_array;
use function is_object;
use function ltrim;
use function parse_str;
use function parse_url;
use function preg_replace;
use function preg_replace_callback;
use function sprintf;
use function str_starts_with;
use function strpos;
use function strrpos;
use function substr;
use function trim;
use function urldecode;
use const FILTER_VALIDATE_URL;

class Uri
{
    /**
     * @Annotation\TwigFilter()
     * @Annotation\TwigFunction()
     */
    public static function buildHttpQuery(array|object $data, ?string $parent = null): array
    {
        $result = [];

        if (is_object($data)) {
            $data = get_object_vars($data);
        }

        foreach ($data as $key => $value) {
            $newKey = $parent ? sprintf('%s[%s]', $parent, $key) : $key;

            if (!$value instanceof CURLFile && (is_array($value) || is_object($value))) {
                /** @noinspection SlowArrayOperationsInLoopInspection */
                $result = array_merge($result, self::buildHttpQuery($value, $newKey));
            } else {
                $result[$newKey] = $value;
            }
        }

        return $result;
    }

    /**
     * @Annotation\TwigFilter ()
     */
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

    /**
     * @Annotation\TwigFilter()
     */
    public static function arrayFromQueryString(string $query): array
    {
        $query = preg_replace_callback('#(?:^|(?<=&))[^=[]+#', static fn ($match) => bin2hex(urldecode($match[0])), $query);

        parse_str($query, $values);

        return array_combine(array_map('hex2bin', array_keys($values)), $values);
    }

    /**
     * @Annotation\TwigFilter()
     */
    public static function parseHeaders(string $rawHeaders = ''): array
    {
        if (function_exists('http_parse_headers')) {
            return http_parse_headers($rawHeaders);
        }

        $key = '';
        $headers = [];

        foreach (explode("\n", $rawHeaders) as $header) {
            $h = explode(':', $header, 2);

            if (isset($h[1])) {
                if (!isset($headers[$h[0]])) {
                    $headers[$h[0]] = trim($h[1]);
                } elseif (is_array($headers[$h[0]])) {
                    $headers[$h[0]][] = trim($h[1]);
                } else {
                    $headers[$h[0]] = [
                        $headers[$h[0]],
                        trim($h[1]),
                    ];
                }
                $key = $h[0];
            } elseif (str_starts_with($h[0], "\t")) {
                $headers[$key] .= "\r\n\t" . trim($h[0]);
            } elseif ('' === $key) {
                $headers[0] = trim($h[0]);
            }
        }

        return $headers;
    }

    /**
     * @Annotation\TwigFunction()
     */
    #[Pure]
    public static function isUrl(string $url): bool
    {
        $url = filter_var($url, FILTER_SANITIZE_URL);

        /** @noinspection BypassedUrlValidationInspection */
        return false !== filter_var($url, FILTER_VALIDATE_URL);
    }

    /**
     * @Annotation\TwigFilter()
     */
    public static function parseQueryPath(string $path): bool|string
    {
        $path = '/' . ltrim($path, '/');
        $path = preg_replace('#[\x00-\x1F\x7F]#', '', $path);

        while (false !== $pos = strpos($path, '/../')) {
            $leftSlashNext = strrpos(substr($path, 0, $pos), '/');

            if (false === $leftSlashNext) {
                return false;
            }

            $path = substr($path, 0, $leftSlashNext + 1) . substr($path, $pos + 4);
        }

        return $path;
    }
}
