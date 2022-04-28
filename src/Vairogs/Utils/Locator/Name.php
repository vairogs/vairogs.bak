<?php declare(strict_types = 1);

namespace Vairogs\Utils\Locator;

use Vairogs\Utils\Helper\Text;
use function array_pop;
use function explode;
use function implode;
use function preg_match;
use function preg_quote;
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
        return Text::replacePattern(pattern: '/^\\\*/', text: (string) $this);
    }

    public function key(): string
    {
        return strtolower(string: $this->normalize());
    }

    public function getNamespace(): self
    {
        $namespaceParts = $this->parts;
        array_pop(array: $namespaceParts);

        return new self(name: implode(separator: '\\', array: $namespaceParts));
    }

    public function inNamespace(self $namespace): bool
    {
        return (bool) preg_match(pattern: '/^' . preg_quote(str: $namespace->key(), delimiter: '\\') . '/', subject: $this->getNamespace()->key());
    }
}
