<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Helper;

use Exception;
use LogicException;
use Vairogs\Component\Utils\Twig\Annotation;
use function array_rand;
use function count;
use function function_exists;
use function is_array;
use function random_int;
use function str_shuffle;
use function str_split;

class Generator
{
    public const PASS_LOWERCASE = 'abcdefghijklmnopqrstuvwxyz';
    public const PASS_UPPERCASE = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    public const PASS_DIGITS = '0123456789';
    public const PASS_SYMBOLS = '!@#$%^&*()_-=+;:.,?';
    public const RAND_BASIC = self::PASS_LOWERCASE . self::PASS_UPPERCASE . self::PASS_DIGITS;
    public const RAND_EXTENDED = self::RAND_BASIC . self::PASS_SYMBOLS;
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
     * @throws LogicException
     * @throws Exception
     */
    #[Annotation\TwigFunction]
    public function generate(int $length = 20): string
    {
        if (empty($this->sets)) {
            throw new LogicException(message: 'At least one set must be used!');
        }

        $all = $unique = '';

        foreach ($this->sets as $set) {
            if (is_array(value: $split = str_split(string: $set))) {
                $unique .= $set[$this->tweak($split)];
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

    public function useLower(): static
    {
        $this->sets[self::LOWER] = $this->lowerCase;

        return $this;
    }

    public function useUpper(): static
    {
        $this->sets[self::UPPER] = $this->upperCase;

        return $this;
    }

    public function useDigits(): static
    {
        $this->sets[self::DIGITS] = $this->digits;

        return $this;
    }

    public function useSymbols(): static
    {
        $this->sets[self::SYMBOLS] = $this->symbols;

        return $this;
    }

    public function setLowerCase(string $lowerCase): static
    {
        $this->lowerCase = $lowerCase;

        return $this;
    }

    public function setUpperCase(string $upperCase): static
    {
        $this->upperCase = $upperCase;

        return $this;
    }

    public function setDigits(string $digits): static
    {
        $this->digits = $digits;

        return $this;
    }

    public function setSymbols(string $symbols): static
    {
        $this->symbols = $symbols;

        return $this;
    }

    public function reset(): static
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
