<?php declare(strict_types = 1);

namespace Vairogs\Functions;

use CURLFile;
use JetBrains\PhpStorm\Pure;
use ReflectionException;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

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
use function parse_str;
use function parse_url;
use function preg_match;
use function preg_replace_callback;
use function sprintf;
use function str_replace;
use function urldecode;

use const FILTER_SANITIZE_URL;
use const FILTER_VALIDATE_URL;

final class Uri
{
    /**
     * @throws ReflectionException
     */
    public function buildHttpQueryArray(array|object $input, ?string $parent = null): array
    {
        $result = [];

        foreach ((new Php())->getArray(input: $input) as $key => $value) {
            $newKey = match ($parent) {
                null => $key,
                default => sprintf('%s[%s]', $parent, $key),
            };

            $result = $this->getResult(result: $result, key: $newKey, value: $value);
        }

        return $result;
    }

    /**
     * @throws ReflectionException
     */
    public function buildArrayFromObject(object $object): array
    {
        parse_str(string: $this->buildHttpQueryString($object), result: $result);

        return $result;
    }

    /**
     * @throws ReflectionException
     */
    public function buildHttpQueryString(object $object): string
    {
        return http_build_query(data: $this->buildHttpQueryArray(input: $object));
    }

    public function urlEncode(string $url): string
    {
        $urlParsed = parse_url(url: $url);

        $port = (string) ($urlParsed['port'] ?? '');
        $query = $urlParsed['query'] ?? '';

        if ('' !== $query) {
            /** @var string $query */
            $query = '?' . http_build_query(data: $this->arrayFromQueryString(query: $query));
        }

        if ($port && ':' !== $port[0]) {
            $port = ':' . $port;
        }

        return $urlParsed['scheme'] . '://' . $urlParsed['host'] . $port . ($urlParsed['path'] ?? '') . $query;
    }

    public function arrayFromQueryString(string $query): array
    {
        parse_str(string: (string) preg_replace_callback(pattern: '#(?:^|(?<=&))[^=[]+#', callback: static fn ($match) => bin2hex(string: urldecode(string: $match[0])), subject: $query), result: $values);

        return array_combine(keys: array_map(callback: 'hex2bin', array: array_keys(array: $values)), values: $values);
    }

    public function parseHeaders(string $rawHeaders = ''): array
    {
        $headers = [];
        $headerArray = str_replace(search: '\\r', replace: '', subject: $rawHeaders);
        $headerArray = explode(separator: '\\n', string: $headerArray);

        foreach ($headerArray as $item) {
            $header = explode(separator: ': ', string: $item, limit: 2);

            if ($header[0] && !$header[1]) {
                $headers['status'] = $header[0];
            } elseif ($header[0] && $header[1]) {
                $headers[$header[0]] = $header[1];
            }
        }

        return $headers;
    }

    public function getRawHeaders(HeaderBag $headerBag): string
    {
        $string = '';

        foreach ($headerBag->all() as $header => $value) {
            $string .= $header . ': ' . $value[0] . '\r\n';
        }

        return $string;
    }

    #[Pure]
    public function isUrl(string $url): bool
    {
        /* @noinspection BypassedUrlValidationInspection */
        return false !== filter_var(value: filter_var(value: $url, filter: FILTER_SANITIZE_URL), filter: FILTER_VALIDATE_URL);
    }

    public function getSchema(Request $request): string
    {
        return (new Http())->isHttps(request: $request) ? Constants\Http::SCHEMA_HTTPS : Constants\Http::SCHEMA_HTTP;
    }

    public function isAbsolute(string $path): bool
    {
        return str_starts_with(haystack: $path, needle: '//') || preg_match(pattern: '#^[a-z-]{3,}://#i', subject: $path);
    }

    public function routeExists(RouterInterface $router, string $route): bool
    {
        return null !== $router->getRouteCollection()->get(name: $route);
    }

    /**
     * @throws ReflectionException
     */
    private function getResult(array $result, string $key, mixed $value): array
    {
        if (!$value instanceof CURLFile && (is_array(value: $value) || is_object(value: $value))) {
            return array_merge($result, $this->buildHttpQueryArray(input: $value, parent: $key));
        }

        $result[$key] = $value;

        return $result;
    }
}
