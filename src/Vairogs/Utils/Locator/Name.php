<?php declare(strict_types = 1);

namespace Vairogs\Utils\Locator;

use PhpParser\Node\Name as PhpParserName;

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

    public function createNode(): PhpParserName
    {
        return new PhpParserName(name: $this->parts);
    }

    public function isDefined(bool $autoload = true): bool
    {
        return class_exists(class: (string) $this, autoload: $autoload)
            || interface_exists(interface: (string) $this, autoload: $autoload)
            || trait_exists(trait: (string) $this, autoload: $autoload)
            || function_exists(function: (string) $this);
    }

    public function normalize(): string
    {
        return preg_replace(pattern: '/^\\\*/', replacement: '', subject: (string) $this);
    }

    public function keyize(): string
    {
        return strtolower(string: $this->normalize());
    }

    public function getBasename(): self
    {
        return new self(name: (string) end(array: $this->parts));
    }

    public function getNamespace(): self
    {
        $parts = $this->parts;
        array_pop(array: $parts);

        return new self(name: implode(separator: '\\', array: $parts));
    }

    public function inNamespace(self $namespace): bool
    {
        return (bool) preg_match(pattern: '/^' . preg_quote(str: $namespace->keyize(), delimiter: '\\') . '/', subject: $this->getNamespace()->keyize());
    }
}
