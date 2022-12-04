<?php declare(strict_types = 1);

namespace Vairogs\Utils;

use Vairogs\Functions\Abstraction;
use Vairogs\Functions\Constants\Symbol;

use function count;
use function str_split;

final class Generator extends Abstraction
{
    /** @var array<string, string> */
    private array $sets = [];

    private string $digits = Symbol::DIGITS;
    private string $latvianLower = Symbol::LV_LOWERCASE;
    private string $latvianUpper = Symbol::LV_UPPERCASE;
    private string $lowerCase = Symbol::EN_LOWERCASE;
    private string $symbols = Symbol::SYMBOLS;
    private string $upperCase = Symbol::EN_UPPERCASE;

    public function generate(int $length = 32): string
    {
        [$all, $unique, ] = $this->fillSets();

        if ([] !== $parts = str_split(string: $all)) {
            $unique = $this->fillUnique(unique: $unique, parts: $parts, length: $length);
        }

        return $this->shuffle(string: $unique);
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

    /**
     * @return array<string, string>
     */
    public function getSets(): array
    {
        return $this->sets;
    }

    /**
     * @return array<int, string>
     */
    private function fillSets(): array
    {
        $all = $unique = '';

        foreach ($this->sets as $set) {
            if ([] !== $parts = str_split(string: $set)) {
                $unique .= $set[$this->pick(array: $parts)];
                $all .= $set;
            }
        }

        return [$all, $unique, ];
    }

    /**
     * @param array<int, string> $parts
     */
    private function fillUnique(string $unique, array $parts, int $length): string
    {
        $setsCount = count(value: $this->sets);

        for ($i = 0; $i < $length - $setsCount; $i++) {
            $unique .= $parts[$this->pick(array: $parts)];
        }

        return $unique;
    }
}
