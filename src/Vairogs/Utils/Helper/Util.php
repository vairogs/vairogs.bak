<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use Symfony\Component\HttpFoundation\Request;
use Vairogs\Utils\Twig\Attribute;
use function array_fill;
use function file_get_contents;
use function function_exists;
use function implode;
use function preg_match;

final class Util
{
    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function isPrime(int $number): bool
    {
        if (null !== $prime = self::isPrimeGmp(number: (string) $number)) {
            return $prime;
        }

        if (null !== $prime = self::isPrimeBelow1000(number: $number)) {
            return $prime;
        }

        preg_match(pattern: '#^1?$|^(11+?)\1+$#', subject: implode(separator: '1', array: array_fill(start_index: 0, count: $number, value: null)), matches: $matches);

        return isset($matches[1]);
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function isPrimeBelow1000(int $number): ?bool
    {
        if (1000 <= $number) {
            return null;
        }

        if (1 === $number) {
            return false;
        }

        for ($x = 2; $x < $number; $x++) {
            if (0 === $number % $x) {
                return false;
            }
        }

        return true;
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function getRequestIdentity(Request $request, string $ipUrl = 'https://ident.me'): array
    {
        $additionalData = [
            'actualIp' => file_get_contents(filename: $ipUrl),
            'uuid' => $request->server->get(key: 'REQUEST_TIME') . Identification::getUniqueId(length: 32),
        ];

        return array_merge(Uri::buildArrayFromObject(object: $request), $additionalData);
    }

    private static function isPrimeGmp(string $number): ?bool
    {
        if (function_exists(function: 'gmp_prob_prime')) {
            return match (gmp_prob_prime(num: $number)) {
                0 => false,
                2 => true,
                default => null
            };
        }

        return null;
    }
}
