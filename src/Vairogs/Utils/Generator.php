<?php declare(strict_types = 1);

namespace Vairogs\Utils;

use Vairogs\Extra\Constants\Symbol;
use function array_rand;
use function count;
use function str_shuffle;
use function str_split;

final class Generator
{
    private array $sets = [];

    private string $lowerCase = Symbol::EN_LOWERCASE;
    private string $upperCase = Symbol::EN_UPPERCASE;
    private string $digits = Symbol::DIGITS;
    private string $symbols = Symbol::SYMBOLS;
    private string $latvianUpper = Symbol::LV_UPPERCASE;
    private string $latvianLower = Symbol::LV_LOWERCASE;

    public function generate(int $length = 32): string
    {
        $all = $unique = '';

        foreach ($this->sets as $set) {
            if ([] !== $split = str_split(string: $set)) {
                $unique .= $set[array_rand(array: $split)];
                $all .= $set;
            }
        }

        if ([] !== $all = str_split(string: $all)) {
            $setsCount = count(value: $this->sets);

            for ($i = 0; $i < $length - $setsCount; $i++) {
                /* @noinspection NonSecureArrayRandUsageInspection */
                $unique .= $all[array_rand(array: $all)];
            }
        }

        /* @noinspection NonSecureStrShuffleUsageInspection */
        return str_shuffle(string: $unique);
    }

    public function useLower(): self
    {
        $this->sets['lower'] = $this->lowerCase;

        return $this;
    }

    public function useUpper(): self
    {
        $this->sets['upper'] = $this->upperCase;

        return $this;
    }

    public function useDigits(): self
    {
        $this->sets['digits'] = $this->digits;

        return $this;
    }

    public function useSymbols(): self
    {
        $this->sets['symbols'] = $this->symbols;

        return $this;
    }

    public function useLatvianLowerCase(): self
    {
        $this->sets['latvianlower'] = $this->latvianLower;

        return $this;
    }

    public function useLatvianUpperCase(): self
    {
        $this->sets['latvianupper'] = $this->latvianUpper;

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

    public function setLatvianLowerCase(string $latvianLowerCase): self
    {
        $this->latvianLower = $latvianLowerCase;

        return $this;
    }

    public function setLatvianUpperCase(string $latvianUpperCase): self
    {
        $this->latvianUpper = $latvianUpperCase;

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
}
