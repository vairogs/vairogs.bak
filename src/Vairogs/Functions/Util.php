<?php declare(strict_types = 1);

namespace Vairogs\Functions;

use Vairogs\Functions\Handler\FunctionHandler;

use function acos;
use function array_fill;
use function cos;
use function deg2rad;
use function function_exists;
use function gmp_prob_prime;
use function implode;
use function preg_match;
use function rad2deg;
use function round;
use function sin;

final class Util
{
    public function isPrime(int $number, bool $override = false): bool
    {
        $function = (new FunctionHandler(function: 'isPrimeFunction', instance: new self()));
        $below = (new FunctionHandler(function: 'isPrimeBelow1000', instance: new self()))->next(handler: $function);

        return (bool) (new FunctionHandler(function: 'isPrimeGmp', instance: new self()))->next(handler: $below)->handle($number, $override);
    }

    public function isPrimeFunction(int $number): bool
    {
        preg_match(pattern: '#^1?$|^(11+?)\1+$#', subject: implode(separator: '1', array: array_fill(start_index: 0, count: $number, value: null)), matches: $matches);

        return isset($matches[1]);
    }

    public function isPrimeBelow1000(int $number): ?bool
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

    public function isPrimeGmp(int $number, bool $override = false): ?bool
    {
        if (!$override && function_exists(function: 'gmp_prob_prime')) {
            return match (gmp_prob_prime(num: (string) $number)) {
                0 => false,
                2 => true,
                default => null,
            };
        }

        return null;
    }

    public function distanceBetweenPoints(float $latitude1, float $longitude1, float $latitude2, float $longitude2, bool $km = true, int $precision = 4): float
    {
        if ($latitude1 === $latitude2 && $longitude1 === $longitude2) {
            return 0.0;
        }

        $lat1rad = deg2rad(num: $latitude1);
        $lat2rad = deg2rad(num: $latitude2);

        $distance = rad2deg(num: acos(num: (sin(num: $lat1rad) * sin(num: $lat2rad)) + (cos(num: $lat1rad) * cos(num: $lat2rad) * cos(num: deg2rad(num: $longitude1 - $longitude2)))));

        return round(num: $km ? $distance * 111.18957696 : $distance * 69.09, precision: $precision);
    }
}
