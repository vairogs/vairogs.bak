<?php declare(strict_types = 1);

namespace Vairogs\Utils\Locator;

use Vairogs\Utils\Helper\Text;
use function explode;
use function implode;
use function strtolower;

class Name
{
    private array $parts;

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
        return (new Text())->replacePattern(pattern: '/^\\\*/', text: (string) $this);
    }

    public function key(): string
    {
        return strtolower(string: $this->normalize());
    }
}
