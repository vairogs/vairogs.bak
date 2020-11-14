<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Helper;

use CURLFile;
use function array_combine;
use function array_keys;
use function array_map;
use function array_merge;
use function bin2hex;
use function explode;
use function filter_var;
use function function_exists;
use function get_object_vars;
use function http_parse_headers;
use function is_array;
use function is_object;
use function ltrim;
use function parse_str;
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
     * @param array|object $data
     * @param string|null $parent
     *
     * @return array
     */
    public static function buildHttpQuery(array|object $data, ?string $parent = null): array
    {
        $result = [];

        if (is_object($data)) {
            $data = get_object_vars($data);
        }

        foreach ($data as $key => $value) {
            if ($parent) {
                $new_key = sprintf('%s[%s]', $parent, $key);
            } else {
                $new_key = $key;
            }

            if (!$value instanceof CURLFile && (is_array($value) || is_object($value))) {
                /** @noinspection SlowArrayOperationsInLoopInspection */
                $result = array_merge($result, self::buildHttpQuery($value, $new_key));
            } else {
                $result[$new_key] = $value;
            }
        }

        return $result;
    }

    public static function urlEncode(string $url): string
    {
        $url_parsed = parse_url($url);

        $scheme = $url_parsed['scheme'] . '://';
        $host = $url_parsed['host'];
        $port = $url_parsed['port'] ?? null;
        $path = $url_parsed['path'] ?? null;
        $query = $url_parsed['query'] ?? null;

        if (null !== $query) {
            /** @var string $query */
            $query = '?' . http_build_query(self::arrayFromQueryString($query));
        }

        if ($port && ':' !== $port[0]) {
            $port = ':' . $port;
        }

        return $scheme . $host . $port . $path . $query;
    }

    public static function arrayFromQueryString(string $query): array
    {
        $query = preg_replace_callback('/(?:^|(?<=&))[^=[]+/', static function ($match) {
            return bin2hex(urldecode($match[0]));
        }, $query);

        parse_str($query, $values);

        return array_combine(array_map('hex2bin', array_keys($values)), $values);
    }

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
            } elseif (!$key) {
                $headers[0] = trim($h[0]);
            }
        }

        return $headers;
    }

    public static function isUrl(string $url): bool
    {
        $url = filter_var($url, FILTER_SANITIZE_URL);

        /** @noinspection BypassedUrlValidationInspection */
        return false !== filter_var($url, FILTER_VALIDATE_URL);
    }

    /**
     * @param string $path
     *
     * @return string|bool
     */
    public static function parseQueryPath(string $path)
    {
        $path = '/' . ltrim($path, '/');
        $path = preg_replace('/[\x00-\x1F\x7F]/', '', $path);

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
