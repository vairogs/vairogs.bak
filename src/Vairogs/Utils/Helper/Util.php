<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use Vairogs\Utils\Handler\FunctionHandler;
use Vairogs\Utils\Twig\Attribute;
use function array_fill;
use function function_exists;
use function implode;
use function ltrim;
use function preg_match;
use const PHP_INT_MAX;

final class Util
{
    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function isPrime(int $number): bool
    {
        $function = (new FunctionHandler())->setFunction(functionName: 'isPrimeFunction', instance: new self());
        $below = (new FunctionHandler())->setFunction(functionName: 'isPrimeBelow1000', instance: new self())->setNext(handler: $function);

        return (new FunctionHandler())->setFunction(functionName: 'isPrimeGmp', instance: new self())->setNext(handler: $below)->handle($number);
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function isPrimeFunction(int $number): bool
    {
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

        for ($x = 2; $x < $number; $x++) {
            if (0 === $number % $x) {
                return false;
            }
        }

        return 1 !== $number;
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function makeOneDimension(array $array, string $base = '', string $separator = '.', bool $onlyLast = false, int $depth = 0, int $maxDepth = PHP_INT_MAX, array $result = []): array
    {
        if ($depth <= $maxDepth) {
            foreach ($array as $key => $value) {
                $key = ltrim(string: $base . '.' . $key, characters: '.');

                if (Iteration::isAssociative(array: $value)) {
                    $result = self::makeOneDimension(array: $value, base: $key, separator: $separator, onlyLast: $onlyLast, depth: $depth + 1, maxDepth: $maxDepth, result: $result);

                    if ($onlyLast) {
                        continue;
                    }
                }

                $result[$key] = $value;
            }
        }

        return $result;
    }

    public static function isPrimeGmp(int $number): ?bool
    {
        if (function_exists(function: 'gmp_prob_prime')) {
            return match (gmp_prob_prime(num: (string) $number)) {
                0 => false,
                2 => true,
                default => null
            };
        }

        return null;
    }
}
