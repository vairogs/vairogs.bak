<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use Symfony\Component\HttpFoundation\Request;
use Vairogs\Utils\Twig\Annotation;
use function array_fill;
use function file_get_contents;
use function implode;
use function preg_match;

final class Util
{
    #[Annotation\TwigFunction]
    public static function isPrime(int $number): bool
    {
        preg_match(pattern: '#^1?$|^(11+?)\1+$#', subject: implode(separator: '1', array: array_fill(start_index: 0, count: $number, value: null)), matches: $matches);

        return isset($matches[1]);
    }

    #[Annotation\TwigFunction]
    #[Annotation\TwigFilter]
    public static function getRequestIdentity(Request $request): array
    {
        $requestData = Uri::buildArrayFromObject($request);
        $additionalData = [
            'actualIp' => file_get_contents('https://ident.me'),
            'uuid' => time() . Identification::getUniqueId(),
        ];

        return array_merge($requestData, $additionalData);
    }
}
