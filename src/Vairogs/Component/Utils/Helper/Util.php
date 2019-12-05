<?php declare(strict_types = 1);

namespace Vairogs\Utils;

use function array_fill;
use function implode;
use function preg_match;
use const null;

class Util
{
    /**
     * @param int $number
     *
     * @return bool
     */
    public static function isPrime(int $number): bool
    {
        preg_match('/^1?$|^(11+?)\1+$/', implode(1, array_fill(0, $number, null)), $matches);

        return isset($matches[1]);
    }
}
