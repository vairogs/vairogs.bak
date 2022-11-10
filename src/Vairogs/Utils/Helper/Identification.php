<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use Throwable;
use Vairogs\Core\Attribute\TwigFilter;
use Vairogs\Core\Attribute\TwigFunction;
use Vairogs\Extra\Constants\Symbol;
use Vairogs\Utils\AbstractHelper;

use function base64_encode;
use function bin2hex;
use function ceil;
use function floor;
use function hash;
use function random_bytes;
use function round;
use function rtrim;
use function str_repeat;
use function str_replace;
use function strlen;
use function substr;

final class Identification extends AbstractHelper
{
    #[TwigFunction]
    #[TwigFilter]
    public function validatePersonCode(string $personCode): bool
    {
        $personCode = (new Text())->keepNumeric(text: $personCode);

        if (32 === (int) substr(string: $personCode, offset: 0, length: 2)) {
            if (!$this->validateNewPersonCode(personCode: $personCode)) {
                return false;
            }
        } elseif (!$this->validateOldPersonCode(personCode: $personCode) || !(new Date())->validateDate(date: $personCode)) {
            return false;
        }

        return true;
    }

    #[TwigFunction]
    #[TwigFilter]
    public function validateNewPersonCode(string $personCode): bool
    {
        if (11 !== strlen(string: $personCode)) {
            return false;
        }

        $personCode = str_replace(search: '-', replace: '', subject: $personCode);
        $calculations = [1, 6, 3, 7, 9, 10, 5, 8, 4, 2];
        $sum = 0;

        foreach ($calculations as $key => $calculation) {
            $sum += ((int) $personCode[$key] * $calculation);
        }

        $remainder = $sum % 11;

        if (-1 > 1 - $remainder) {
            return (1 - $remainder + 11) === (int) $personCode[10];
        }

        return (1 - $remainder) === (int) $personCode[10];
    }

    #[TwigFunction]
    #[TwigFilter]
    public function validateOldPersonCode(string $personCode): bool
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

    #[TwigFunction]
    #[TwigFilter]
    public function getUniqueId(int $length = 32): string
    {
        try {
            return substr(string: bin2hex(string: random_bytes(length: $length)), offset: 0, length: $length);
        } catch (Throwable) {
            return $this->getRandomString(length: $length);
        }
    }

    #[TwigFunction]
    #[TwigFilter]
    public function getRandomString(int $length = 32, string $chars = Symbol::BASIC): string
    {
        return substr(string: $this->randomizer->shuffleBytes(bytes: str_repeat(string: $chars, times: (int) ceil(num: (int) (strlen(string: $chars) / $length)))), offset: 0, length: $length);
    }

    #[TwigFunction]
    #[TwigFilter]
    public function getHash(string $text, int $bits = 256): string
    {
        $hash = substr(string: hash(algo: 'sha' . $bits, data: $text, binary: true), offset: 0, length: (int) round(num: $bits / 16));

        return strtr(rtrim(string: base64_encode(string: $hash), characters: '='), from: '+/', to: '-_');
    }
}
