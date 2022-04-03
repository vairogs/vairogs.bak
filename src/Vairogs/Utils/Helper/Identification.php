<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use Exception;
use Vairogs\Utils\Generator;
use Vairogs\Utils\Twig\Attribute;
use function bin2hex;
use function ceil;
use function floor;
use function random_bytes;
use function str_repeat;
use function str_replace;
use function str_shuffle;
use function strlen;
use function substr;

final class Identification
{
    #[Attribute\TwigFunction]
    public static function validatePersonCode(string $personCode): bool
    {
        $personCode = Text::keepNumeric(string: $personCode);

        if (32 === (int) substr(string: $personCode, offset: 0, length: 2)) {
            if (!self::validateNewPersonCode(personCode: $personCode)) {
                return false;
            }
        } elseif (!Date::validateDate(date: $personCode) || !self::validateOldPersonCode(personCode: $personCode)) {
            return false;
        }

        return true;
    }

    #[Attribute\TwigFunction]
    public static function validateNewPersonCode(string $personCode): bool
    {
        if (11 !== strlen(string: $personCode)) {
            return false;
        }

        $personCode = str_replace(search: '-', replace: '', subject: $personCode);
        $calculations = [1, 6, 3, 7, 9, 10, 5, 8, 4, 2];
        $sum = 0;

        foreach ($calculations as $key => $calculation) {
            $sum += ($personCode[$key] * $calculation);
        }

        $remainder = $sum % 11;

        if (-1 > 1 - $remainder) {
            return (1 - $remainder + 11) === (int) $personCode[10];
        }

        return (1 - $remainder) === (int) $personCode[10];
    }

    #[Attribute\TwigFunction]
    public static function validateOldPersonCode(string $personCode): bool
    {
        if (11 !== strlen(string: $personCode)) {
            return false;
        }

        $personCode = str_replace(search: '-', replace: '', subject: $personCode);
        $check = '01060307091005080402';
        $checksum = 1;

        for ($i = 0; $i < 10; $i++) {
            $checksum -= (int) $personCode[$i] * (int) substr(string: $check, offset: $i * 2, length: 2);
        }

        return (int) ($checksum - floor(num: $checksum / 11) * 11) === (int) $personCode[10];
    }

    #[Attribute\TwigFunction]
    public static function getUniqueId(int $length = 20): string
    {
        try {
            return substr(string: bin2hex(string: random_bytes(length: $length)), offset: 0, length: $length);
        } catch (Exception) {
            return self::getRandomString(length: $length);
        }
    }

    #[Attribute\TwigFunction]
    public static function getRandomString(int $length = 20, string $chars = Generator::RAND_BASIC): string
    {
        /* @noinspection NonSecureStrShuffleUsageInspection */
        return substr(string: str_shuffle(string: str_repeat(string: $chars, times: (int) ceil(num: (int) (strlen(string: $chars) / $length)))), offset: 0, length: $length);
    }
}
