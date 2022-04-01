<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use InvalidArgumentException;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Vairogs\Extra\Constants;
use Vairogs\Utils\Twig\Attribute;

final class Http
{
    #[Attribute\TwigFunction]
    public static function isHttps(Request $req): bool
    {
        return self::checkHttps(req: $req) || self::checkServerPort(req: $req) || self::checkHttpXForwardedSsl(req: $req) || self::checkHttpXForwardedProto(req: $req);
    }

    #[Attribute\TwigFunction]
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
    public static function getMethods(): array
    {
        return Iteration::arrayValuesFiltered(input: Php::getClassConstants(class: Request::class), with: 'METHOD_');
    }

    private static function checkHttps(Request $req): bool
    {
        return $req->server->has(key: Constants\Http::HEADER_HTTPS) && 'on' === $req->server->get(key: Constants\Http::HEADER_HTTPS);
    }

    private static function checkServerPort(Request $req): bool
    {
        return $req->server->has(key: Constants\Http::HEADER_PORT) && Constants\Http::HTTPS === (int) $req->server->get(key: Constants\Http::HEADER_PORT);
    }

    private static function checkHttpXForwardedSsl(Request $req): bool
    {
        return $req->server->has(key: Constants\Http::HEADER_SSL) && 'on' === $req->server->get(key: Constants\Http::HEADER_SSL);
    }

    private static function checkHttpXForwardedProto(Request $req): bool
    {
        return $req->server->has(key: Constants\Http::HEADER_PROTO) && 'https' === $req->server->get(key: Constants\Http::HEADER_PROTO);
    }
}
