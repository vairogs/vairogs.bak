<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Helper;

use Exception;
use JetBrains\PhpStorm\Pure;
use LogicException;
use Vairogs\Component\Utils\Twig\Annotation;
use function array_rand;
use function ceil;
use function count;
use function function_exists;
use function is_array;
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

    #[Annotation\TwigFunction]
    #[Pure]
    public static function getRandomString(int $length = 20, string $chars = self::RAND_BASIC): string
    {
        /* @noinspection NonSecureStrShuffleUsageInspection */
        return substr(str_shuffle(str_repeat($chars, (int) ceil((int) (strlen($chars) / $length)))), 0, $length);
    }

    /**
     * @throws LogicException
     * @throws Exception
     */
    #[Annotation\TwigFunction]
    public function generate(int $length = 20): string
    {
        if (empty($this->sets)) {
            throw new LogicException('At least one set must be used!');
        }

        $all = $unique = '';

        foreach ($this->sets as $set) {
            if (is_array($split = str_split($set))) {
                $unique .= $set[$this->tweak($split)];
                $all .= $set;
            }
        }

        if (is_array($all = str_split($all))) {
            $setsCount = count($this->sets);

            for ($i = 0; $i < $length - $setsCount; $i++) {
                $unique .= $all[$this->tweak($all)];
            }
        }

        /* @noinspection NonSecureStrShuffleUsageInspection */
        return str_shuffle($unique);
    }

    /**
     * @throws Exception
     */
    private function tweak(array $array): array|int|string
    {
        if (function_exists('random_int')) {
            return random_int(0, count($array) - 1);
        }

        return array_rand($array);
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
}
