<?php declare(strict_types = 1);

namespace Vairogs\Utils\Locator;

use function explode;
use function implode;
use function preg_replace;
use function strtolower;

class Name
{
    private readonly array $parts;

    public function __construct(string $name)
    {
        $this->parts = explode(separator: '\\', string: $name);
    }

    public function __toString(): string
    {
        return implode(separator: '\\', array: $this->parts);
    }

    public function normalize(): string
    {
        return preg_replace(pattern: '/^\\\*/', replacement: '', subject: (string) $this);
    }

    public function key(): string
    {
        return strtolower(string: $this->normalize());
    }
}
