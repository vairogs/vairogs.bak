<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use InvalidArgumentException;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Vairogs\Extra\Constants;
use Vairogs\Utils\Twig\Attribute;
use function array_merge;
use function file_get_contents;

final class Http
{
    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function isHttps(Request $request): bool
    {
        return self::checkHttps(request: $request) || self::checkServerPort(request: $request) || self::checkHttpXForwardedSsl(request: $request) || self::checkHttpXForwardedProto(request: $request);
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function getRemoteIp(Request $request, bool $trust = false): string
    {
        if (false === $trust) {
            return $request->server->get(key: Constants\Http::REMOTE_ADDR);
        }

        $parameters = [
            Constants\Http::HTTP_CLIENT_IP,
            Constants\Http::HTTP_X_REAL_IP,
            Constants\Http::HTTP_X_FORWARDED_FOR,
        ];

        foreach ($parameters as $parameter) {
            if ($request->server->has(key: $parameter)) {
                return $request->server->get(key: $parameter);
            }
        }

        return $request->server->get(key: Constants\Http::REMOTE_ADDR);
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function getRemoteIpCF(Request $request, bool $trust = false): string
    {
        if ($request->server->has(key: Constants\Http::HTTP_CF_CONNECTING_IP)) {
            return $request->server->get(key: Constants\Http::HTTP_CF_CONNECTING_IP);
        }

        return self::getRemoteIp(request: $request, trust: $trust);
    }

    /**
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function getMethods(): array
    {
        return Iteration::arrayValuesFiltered(input: Php::getClassConstants(class: Request::class), with: 'METHOD_');
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function getRequestIdentity(Request $request, string $ipUrl = 'https://ident.me'): array
    {
        $additionalData = [
            'actualIp' => file_get_contents(filename: $ipUrl),
            'uuid' => $request->server->get(key: 'REQUEST_TIME', default: '') . Identification::getUniqueId(),
        ];

        return array_merge(Uri::buildArrayFromObject(object: $request), $additionalData);
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function isIE(Request $request): bool
    {
        return Text::containsAny(haystack: $request->server->get(key: 'HTTP_USER_AGENT'), needles: ['MSIE', 'Edge', 'Trident/7']);
    }

    public static function checkHttps(Request $request): bool
    {
        return $request->server->has(key: Constants\Http::HEADER_HTTPS) && Constants\Status::ON === $request->server->get(key: Constants\Http::HEADER_HTTPS);
    }

    public static function checkServerPort(Request $request): bool
    {
        return $request->server->has(key: Constants\Http::HEADER_PORT) && Constants\Http::HTTPS === (int) $request->server->get(key: Constants\Http::HEADER_PORT);
    }

    public static function checkHttpXForwardedSsl(Request $request): bool
    {
        return $request->server->has(key: Constants\Http::HEADER_SSL) && Constants\Status::ON === $request->server->get(key: Constants\Http::HEADER_SSL);
    }

    public static function checkHttpXForwardedProto(Request $request): bool
    {
        return $request->server->has(key: Constants\Http::HEADER_PROTO) && 'https' === $request->server->get(key: Constants\Http::HEADER_PROTO);
    }
}
