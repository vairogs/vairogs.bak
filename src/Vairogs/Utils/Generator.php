<?php declare(strict_types = 1);

namespace Vairogs\Utils;

use Exception;
use LogicException;
use function array_rand;
use function count;
use function function_exists;
use function is_array;
use function random_int;
use function str_shuffle;
use function str_split;

final class Generator
{
    final public const PASS_LOWERCASE = 'abcdefghijklmnopqrstuvwxyz';
    final public const PASS_UPPERCASE = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    final public const PASS_DIGITS = '0123456789';
    final public const PASS_SYMBOLS = '!@#$%^&*()_-=+;:.,?';
    final public const RAND_BASIC = self::PASS_LOWERCASE . self::PASS_UPPERCASE . self::PASS_DIGITS;
    final public const RAND_EXTENDED = self::RAND_BASIC . self::PASS_SYMBOLS;
    final public const LOWER = 'lower';
    final public const UPPER = 'upper';
    final public const DIGITS = 'digits';
    final public const SYMBOLS = 'symbols';

    private array $sets = [];
    private string $lowerCase = self::PASS_LOWERCASE;
    private string $upperCase = self::PASS_UPPERCASE;
    private string $digits = self::PASS_DIGITS;
    private string $symbols = self::PASS_SYMBOLS;

    /**
     * @throws LogicException
     * @throws Exception
     */
    public function generate(int $length = 32): string
    {
        if (empty($this->sets)) {
            throw new LogicException(message: 'At least one set must be used!');
        }

        $all = $unique = '';

        foreach ($this->sets as $set) {
            if (is_array(value: $split = str_split(string: $set))) {
                $unique .= $set[$this->tweak(array: $split)];
                $all .= $set;
            }
        }

        if (is_array(value: $all = str_split(string: $all))) {
            $setsCount = count(value: $this->sets);

            for ($i = 0; $i < $length - $setsCount; $i++) {
                $unique .= $all[$this->tweak(array: $all)];
            }
        }

        /* @noinspection NonSecureStrShuffleUsageInspection */
        return str_shuffle(string: $unique);
    }

    public function useLower(): self
    {
        $this->sets[self::LOWER] = $this->lowerCase;

        return $this;
    }

    public function useUpper(): self
    {
        $this->sets[self::UPPER] = $this->upperCase;

        return $this;
    }

    public function useDigits(): self
    {
        $this->sets[self::DIGITS] = $this->digits;

        return $this;
    }

    public function useSymbols(): self
    {
        $this->sets[self::SYMBOLS] = $this->symbols;

        return $this;
    }

    public function setLowerCase(string $lowerCase): self
    {
        $this->lowerCase = $lowerCase;

        return $this;
    }

    public function setUpperCase(string $upperCase): self
    {
        $this->upperCase = $upperCase;

        return $this;
    }

    public function setDigits(string $digits): self
    {
        $this->digits = $digits;

        return $this;
    }

    public function setSymbols(string $symbols): self
    {
        $this->symbols = $symbols;

        return $this;
    }

    public function reset(): self
    {
        $this->sets = [];

        return $this;
    }

    public function getSets(): array
    {
        return $this->sets;
    }

    /**
     * @throws Exception
     */
    private function tweak(array $array): array|int|string
    {
        if (function_exists(function: 'random_int')) {
            return random_int(min: 0, max: count(value: $array) - 1);
        }

        return array_rand(array: $array);
    }
}
