<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use Symfony\Component\HttpFoundation\Request;
use Vairogs\Utils\Twig\Attribute;
use function array_fill;
use function file_get_contents;
use function implode;
use function preg_match;

final class Util
{
    #[Attribute\TwigFunction]
    public static function isPrime(int $number): bool
    {
        preg_match(pattern: '#^1?$|^(11+?)\1+$#', subject: implode(separator: '1', array: array_fill(start_index: 0, count: $number, value: null)), matches: $matches);

        return isset($matches[1]);
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
}
