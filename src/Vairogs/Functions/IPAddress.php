<?php declare(strict_types = 1);

namespace Vairogs\Functions;

use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyInfo\Type;
use Vairogs\Functions\Constants\Http;

use function array_map;
use function count;
use function explode;
use function is_numeric;
use function long2ip;

final class IPAddress
{
    public function getRemoteIp(Request $request, bool $trust = false): string
    {
        $headers = [Http::REMOTE_ADDR, ];

        if ($trust) {
            $headers = [Http::HTTP_CLIENT_IP, Http::HTTP_X_REAL_IP, Http::HTTP_X_FORWARDED_FOR, Http::REMOTE_ADDR, ];
        }

        return (string) (new Iteration())->getFirstMatchAsString(keys: $headers, haystack: $request->server->all());
    }

    public function getRemoteIpCF(Request $request, bool $trust = false): string
    {
        if ($request->server->has(key: Http::HTTP_CF_CONNECTING_IP)) {
            return $request->server->get(key: Http::HTTP_CF_CONNECTING_IP);
        }

        return $this->getRemoteIp(request: $request, trust: $trust);
    }

    #[ArrayShape([
        Type::BUILTIN_TYPE_STRING,
        Type::BUILTIN_TYPE_STRING,
    ])]
    public function getCIDRRange(string $cidr, bool $int = true): array
    {
        if (!$this->isCIDR(cidr: $cidr)) {
            return ['0', '0'];
        }

        [$base, $bits,] = explode(separator: '/', string: $cidr);
        $bits = (int) $bits;
        [$part1, $part2, $part3, $part4,] = array_map('intval', explode(separator: '.', string: $base));
        $sum = ($part1 << 24) + ($part2 << 16) + ($part3 << 8) + $part4;
        $mask = (0 === $bits) ? 0 : (~0 << (32 - $bits));

        $low = $sum & $mask;
        $high = $sum | (~$mask & 0xFFFFFFFF);

        if ($int) {
            return [(string) $low, (string) $high];
        }

        return [long2ip(ip: $low), long2ip(ip: $high)];
    }

    public function isCIDR(string $cidr): bool
    {
        $parts = explode(separator: '/', string: $cidr);

        if (2 === count(value: $parts) && is_numeric(value: $parts[1]) && 32 >= (int) $parts[1]) {
            return (new Validate())->validateIPAddress(ipAddress: $parts[0], deny: false);
        }

        return false;
    }
}
