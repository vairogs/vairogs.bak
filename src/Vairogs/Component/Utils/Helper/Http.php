<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Helper;

use InvalidArgumentException;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;
use Vairogs\Component\Utils\Twig\Annotation;
use function sprintf;

class Http
{
    public const HTTP = 80;
    public const HTTPS = 443;
    private const HEADER_HTTPS = 'HTTPS';
    private const HEADER_PORT = 'SERVER_PORT';
    private const HEADER_SSL = 'HTTP_X_FORWARDED_SSL';
    private const HEADER_PROTO = 'HTTP_X_FORWARDED_PROTO';
    private const REMOTE_ADDR = 'REMOTE_ADDR';

    #[Annotation\TwigFunction]
    public static function isHttps(Request $request): bool
    {
        $checks = [
            self::HEADER_HTTPS,
            self::HEADER_PORT,
            self::HEADER_SSL,
            self::HEADER_PROTO,
        ];

        foreach ($checks as $check) {
            $function = sprintf('check%s', Text::toCamelCase($check));

            if (self::{$function}($request)) {
                return true;
            }
        }

        return false;
    }

    #[Annotation\TwigFunction]
    public static function getRemoteIp(Request $request, bool $trust = false): string
    {
        if (!$trust) {
            return $request->server->get(self::REMOTE_ADDR);
        }

        $parameters = [
            'HTTP_CLIENT_IP',
            'HTTP_X_REAL_IP',
            'HTTP_X_FORWARDED_FOR',
        ];

        foreach ($parameters as $parameter) {
            if ($request->server->has($parameter)) {
                return $request->server->get($parameter);
            }
        }

        return $request->server->get(self::REMOTE_ADDR);
    }

    #[Annotation\TwigFunction]
    public static function getRemoteIpCF(Request $request): string
    {
        if ($request->server->has('HTTP_CF_CONNECTING_IP')) {
            return $request->server->get('HTTP_CF_CONNECTING_IP');
        }

        return $request->server->get(self::REMOTE_ADDR);
    }

    /**
     * @throws InvalidArgumentException
     * @throws ReflectionException
     */
    #[Annotation\TwigFunction]
    public static function getMethods(): array
    {
        return Iteration::arrayValuesFiltered(Php::getClassConstants(Request::class), 'METHOD_');
    }

    protected static function checkHttps(Request $request): bool
    {
        return $request->server->has(self::HEADER_HTTPS) && 'on' === $request->server->get(self::HEADER_HTTPS);
    }

    protected static function checkServerPort(Request $request): bool
    {
        return $request->server->has(self::HEADER_PORT) && self::HTTPS === (int)$request->server->get(self::HEADER_PORT);
    }

    protected static function checkHttpXForwardedSsl(Request $request): bool
    {
        return $request->server->has(self::HEADER_SSL) && 'on' === $request->server->get(self::HEADER_SSL);
    }

    protected static function checkHttpXForwardedProto(Request $request): bool
    {
        return $request->server->has(self::HEADER_PROTO) && 'https' === $request->server->get(self::HEADER_PROTO);
    }
}
