<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyInfo\Type;
use Vairogs\Extra\Constants;
use Vairogs\Twig\Attribute;
use function count;
use function explode;
use function is_numeric;
use function long2ip;

final class IPAddress
{
    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function getRemoteIp(Request $request, bool $trust = false): string
    {
        $headers = [Constants\Http::REMOTE_ADDR, ];

        if ($trust) {
            $headers = [Constants\Http::HTTP_CLIENT_IP, Constants\Http::HTTP_X_REAL_IP, Constants\Http::HTTP_X_FORWARDED_FOR, Constants\Http::REMOTE_ADDR, ];
        }

        return (new Iteration())->getFirstMatchAsString(keys: $headers, haystack: $request->server->all());
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function getRemoteIpCF(Request $request, bool $trust = false): string
    {
        if ($request->server->has(key: Constants\Http::HTTP_CF_CONNECTING_IP)) {
            return $request->server->get(key: Constants\Http::HTTP_CF_CONNECTING_IP);
        }

        return $this->getRemoteIp(request: $request, trust: $trust);
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    #[ArrayShape([
        Type::BUILTIN_TYPE_STRING,
        Type::BUILTIN_TYPE_STRING,
    ])]
    public function getCIDRRange(string $cidr, bool $int = true): array
    {
        if (!$this->isCIDR(cidr: $cidr)) {
            return ['0', '0'];
        }

        [$base, $bits] = explode(separator: '/', string: $cidr);
        /* @var string $base */
        /* @var int $bits */
        [$part1, $part2, $part3, $part4] = explode(separator: '.', string: $base);
        /** @var int $part1 */
        /** @var int $part2 */
        /** @var int $part3 */
        /** @var int $part4 */
        $sum = ($part1 << 24) + ($part2 << 16) + ($part3 << 8) + $part4;
        $mask = (0 === $bits) ? 0 : (~0 << (32 - $bits));

        $low = $sum & $mask;
        $high = $sum | (~$mask & 0xFFFFFFFF);

        if ($int) {
            return [(string) $low, (string) $high];
        }

        return [long2ip(ip: $low), long2ip(ip: $high)];
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function isCIDR(string $cidr): bool
    {
        $parts = explode(separator: '/', string: $cidr);

        if (2 === count(value: $parts) && is_numeric(value: $parts[1]) && 32 >= (int) $parts[1]) {
            return (new Validate())->validateIPAddress(ipAddress: $parts[0], deny: false);
        }

        return false;
    }
}
