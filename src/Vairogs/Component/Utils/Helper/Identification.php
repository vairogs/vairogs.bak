<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Helper;

use Exception;
use Vairogs\Component\Utils\Twig\Annotation;
use function bin2hex;
use function floor;
use function random_bytes;
use function str_replace;
use function strlen;
use function substr;

class Identification
{
    #[Annotation\TwigFunction]
    public static function validatePersonCode(string $personCode): bool
    {
        $personCode = Text::keepNumeric($personCode);
        if (11 !== strlen($personCode)) {
            return false;
        }

        if (32 === (int)substr($personCode, 0, 2)) {
            if (!self::validateNewPersonCode($personCode)) {
                return false;
            }
        } else {
            if (!Date::validateDate($personCode)) {
                return false;
            }
            if (!self::validateOldPersonCode($personCode)) {
                return false;
            }
        }

        return true;
    }

    #[Annotation\TwigFunction]
    public static function validateNewPersonCode(string $personCode): bool
    {
        $personCode = str_replace('-', '', $personCode);
        // @formatter:off
        $calculations = [1, 6, 3, 7, 9, 10, 5, 8, 4, 2,];
        // @formatter:on
        $sum = 0;
        foreach ($calculations as $key => $calculation) {
            $sum += ($personCode[$key] * $calculation);
        }
        $remainder = $sum % 11;
        if (-1 > 1 - $remainder) {
            return (1 - $remainder + 11) === (int)$personCode[10];
        }

        return (1 - $remainder) === (int)$personCode[10];
    }

    #[Annotation\TwigFunction]
    public static function validateOldPersonCode(string $personCode): bool
    {
        $personCode = str_replace('-', '', $personCode);
        $check = '01060307091005080402';
        $checksum = 1;
        for ($i = 0; $i < 10; $i++) {
            $checksum -= (int)$personCode[$i] * (int)substr($check, $i * 2, 2);
        }

        return (int)($checksum - floor($checksum / 11) * 11) === (int)$personCode[10];
    }

    #[Annotation\TwigFunction]
    public static function getUniqueId(int $length = 20): string
    {
        try {
            return substr(bin2hex(random_bytes($length)), 0, $length);
        } catch (Exception) {
            return Generator::getRandomString($length);
        }
    }
}
