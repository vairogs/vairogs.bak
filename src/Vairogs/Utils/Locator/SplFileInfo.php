<?php declare(strict_types = 1);

namespace Vairogs\Utils\Locator;

use Symfony\Component\Finder\SplFileInfo as FinderSplFileInfo;
use function method_exists;

/**
 * @method getContents()
 */
class SplFileInfo
{
    private Reader $reader;

    public function __construct(private readonly FinderSplFileInfo $decorated, private readonly array $types = [])
    {
    }

    public function __call(string $name, array $arguments): mixed
    {
        if (method_exists(object_or_class: $this->decorated, method: $name)) {
            return $this->decorated->{$name}(...$arguments);
        }

        return null;
    }

    public function getReader(string $namespace): Reader
    {
        if (!isset($this->reader)) {
            $this->reader = new Reader(snippet: (string) $this->getContents(), namespace: $namespace, types: $this->types);
        }

        return $this->reader;
    }
}
