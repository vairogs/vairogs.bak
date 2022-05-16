<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use CURLFile;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Vairogs\Extra\Constants;
use Vairogs\Twig\Attribute;
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
use function str_starts_with;
use function urldecode;
use const FILTER_SANITIZE_URL;
use const FILTER_VALIDATE_URL;

final class Uri
{
    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function buildHttpQueryArray(array|object $input, ?string $parent = null): array
    {
        $result = [];

        foreach ((new Php())->getArray(input: $input) as $key => $value) {
            $newKey = match ($parent) {
                null => $key,
                default => sprintf('%s[%s]', $parent, $key)
            };

            if (!$value instanceof CURLFile && (is_array(value: $value) || is_object(value: $value))) {
                /** @noinspection SlowArrayOperationsInLoopInspection */
                $result = array_merge($result, $this->buildHttpQueryArray(input: $value, parent: $newKey));
            } else {
                $result[$newKey] = $value;
            }
        }

        return $result;
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function buildArrayFromObject(object $object): array
    {
        parse_str(string: $this->buildHttpQueryString($object), result: $result);

        return $result;
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function buildHttpQueryString(object $object): string
    {
        return http_build_query(data: $this->buildHttpQueryArray(input: $object));
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
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

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function arrayFromQueryString(string $query): array
    {
        parse_str(string: preg_replace_callback(pattern: '#(?:^|(?<=&))[^=[]+#', callback: static fn ($match) => bin2hex(string: urldecode(string: $match[0])), subject: $query), result: $values);

        return array_combine(keys: array_map(callback: 'hex2bin', array: array_keys(array: $values)), values: $values);
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function parseHeaders(string $rawHeaders = ''): array
    {
        $headers = [];
        $headerArray = str_replace(search: '\\r', replace: '', subject: $rawHeaders);
        $headerArray = explode(separator: '\\n', string: $headerArray);

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
    #[Attribute\TwigFilter]
    public function getRawHeaders(HeaderBag $headers): string
    {
        $string = '';

        foreach ($headers->all() as $header => $value) {
            $string .= $header . ': ' . $value[0] . '\r\n';
        }

        return $string;
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    #[Pure]
    public function isUrl(string $url): bool
    {
        /* @noinspection BypassedUrlValidationInspection */
        return false !== filter_var(value: filter_var(value: $url, filter: FILTER_SANITIZE_URL), filter: FILTER_VALIDATE_URL);
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function getSchema(Request $request): string
    {
        return (new Http())->isHttps(request: $request) ? Constants\Http::SCHEMA_HTTPS : Constants\Http::SCHEMA_HTTP;
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function isAbsolute(string $path): bool
    {
        return str_starts_with(haystack: $path, needle: '//') || preg_match(pattern: '#^[a-z-]{3,}://#i', subject: $path);
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function routeExists(RouterInterface $router, string $route): bool
    {
        return null !== $router->getRouteCollection()->get(name: $route);
    }
}
