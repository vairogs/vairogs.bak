<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Helper;

use Exception;
use JetBrains\PhpStorm\Pure;
use LogicException;
use function array_rand;
use function bin2hex;
use function ceil;
use function count;
use function function_exists;
use function random_bytes;
use function random_int;
use function str_repeat;
use function str_shuffle;
use function str_split;
use function strlen;
use function substr;

class Generator
{
    public const PASS_LOWERCASE = 'abcdefghijklmnopqrstuvwxyz';
    public const PASS_UPPERCASE = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    public const PASS_DIGITS = '0123456789';
    public const PASS_SYMBOLS = '!@#$%^&*()_-=+;:.,?';
    public const RAND_BASIC = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    public const RAND_EXTENDED = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_-=+;:,.?';
    public const LOWER = 'lower';
    public const UPPER = 'upper';
    public const DIGITS = 'digits';
    public const SYMBOLS = 'symbols';

    private array $sets = [];
    private string $lowerCase = self::PASS_LOWERCASE;
    private string $upperCase = self::PASS_UPPERCASE;
    private string $digits = self::PASS_DIGITS;
    private string $symbols = self::PASS_SYMBOLS;

    /**
     * @param int $length
     *
     * @return string
     */
    public static function getUniqueId(int $length = 20): string
    {
        try {
            return substr(bin2hex(random_bytes($length)), 0, $length);
        } catch (Exception) {
            return self::getRandomString($length);
        }
    }

    /**
     * @param int $length
     * @param string $chars
     *
     * @return string
     */
    #[Pure] public static function getRandomString(int $length = 20, string $chars = self::RAND_BASIC): string
    {
        /** @noinspection NonSecureStrShuffleUsageInspection */
        return substr(str_shuffle(str_repeat($chars, (int)ceil((int)(strlen($chars) / $length)))), 0, $length);
    }

    /**
     * @param int $length
     *
     * @return string
     * @throws LogicException
     * @throws Exception
     */
    public function generate(int $length = 20): string
    {
        if (empty($this->sets)) {
            throw new LogicException('At least one set must be used!');
        }

        $all = $unique = '';
        foreach ($this->sets as $set) {
            $unique .= $set[$this->tweak(str_split($set))];
            $all .= $set;
        }
        $all = str_split($all);
        for ($i = 0; $i < $length - count($this->sets); $i++) {
            $unique .= $all[$this->tweak($all)];
        }

        /** @noinspection NonSecureStrShuffleUsageInspection */
        return str_shuffle($unique);
    }

    /**
     * @param array $array
     *
     * @return array|int|string
     * @throws Exception
     */
    private function tweak(array $array): array|int|string
    {
        if (function_exists('random_int')) {
            return random_int(0, count($array) - 1);
        }

        return array_rand($array);
    }

    /**
     * @return Generator
     */
    public function useLower(): Generator
    {
        $this->sets[self::LOWER] = $this->lowerCase;

        return $this;
    }

    /**
     * @return Generator
     */
    public function useUpper(): Generator
    {
        $this->sets[self::UPPER] = $this->upperCase;

        return $this;
    }

    /**
     * @return Generator
     */
    public function useDigits(): Generator
    {
        $this->sets[self::DIGITS] = $this->digits;

        return $this;
    }

    /**
     * @return Generator
     */
    public function useSymbols(): Generator
    {
        $this->sets[self::SYMBOLS] = $this->symbols;

        return $this;
    }

    /**
     * @param string $lowerCase
     *
     * @return Generator
     */
    public function setLowerCase(string $lowerCase): Generator
    {
        $this->lowerCase = $lowerCase;

        return $this;
    }

    /**
     * @param string $upperCase
     *
     * @return Generator
     */
    public function setUpperCase(string $upperCase): Generator
    {
        $this->upperCase = $upperCase;

        return $this;
    }

    /**
     * @param string $digits
     *
     * @return Generator
     */
    public function setDigits(string $digits): Generator
    {
        $this->digits = $digits;

        return $this;
    }

    /**
     * @param string $symbols
     *
     * @return Generator
     */
    public function setSymbols(string $symbols): Generator
    {
        $this->symbols = $symbols;

        return $this;
    }

    /**
     * @return Generator
     */
    public function reset(): Generator
    {
        $this->sets = [];

        return $this;
    }

    /**
     * @return array
     */
    public function getSets(): array
    {
        return $this->sets;
    }
}
