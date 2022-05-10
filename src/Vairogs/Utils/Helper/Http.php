<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use InvalidArgumentException;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Vairogs\Extra\Constants;
use Vairogs\Twig\Attribute;
use function array_merge;
use function file_get_contents;

final class Http
{
    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function isHttps(Request $request): bool
    {
        return $this->checkHttps(request: $request) || $this->checkServerPort(request: $request) || $this->checkHttpXForwardedSsl(request: $request) || $this->checkHttpXForwardedProto(request: $request);
    }

    /**
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function getRequestMethods(): array
    {
        return (new Iteration())->arrayValuesFiltered(input: (new Php())->getClassConstants(class: Request::class), with: 'METHOD_');
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function getRequestIdentity(Request $request, string $ipUrl = 'https://ident.me'): array
    {
        $additionalData = [
            'actualIp' => file_get_contents(filename: $ipUrl),
            'uuid' => $request->server->get(key: 'REQUEST_TIME', default: '') . (new Identification())->getUniqueId(),
        ];

        return array_merge((new Uri())->buildArrayFromObject(object: $request), $additionalData);
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function isIE(Request $request): bool
    {
        return (new Text())->containsAny(haystack: $request->server->get(key: 'HTTP_USER_AGENT'), needles: ['MSIE', 'Edge', 'Trident/7']);
    }

    public function checkHttps(Request $request): bool
    {
        return $request->server->has(key: Constants\Http::HEADER_HTTPS) && Constants\Status::ON === $request->server->get(key: Constants\Http::HEADER_HTTPS);
    }

    public function checkServerPort(Request $request): bool
    {
        return $request->server->has(key: Constants\Http::HEADER_PORT) && Constants\Http::HTTPS === (int) $request->server->get(key: Constants\Http::HEADER_PORT);
    }

    public function checkHttpXForwardedSsl(Request $request): bool
    {
        return $request->server->has(key: Constants\Http::HEADER_SSL) && Constants\Status::ON === $request->server->get(key: Constants\Http::HEADER_SSL);
    }

    public function checkHttpXForwardedProto(Request $request): bool
    {
        return $request->server->has(key: Constants\Http::HEADER_PROTO) && 'https' === $request->server->get(key: Constants\Http::HEADER_PROTO);
    }
}
