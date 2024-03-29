<?php declare(strict_types = 1);

namespace Vairogs\Utils\Locator;

use Symfony\Component\Finder\SplFileInfo as FinderSplFileInfo;

use function method_exists;

/**
 * @method getContents()
 */
readonly class SplFileInfo
{
    private Reader $reader;

    public function __construct(private FinderSplFileInfo $decorated, private array $types = [], string $namespace = '')
    {
        $this->reader = new Reader(snippet: (string) $this->getContents(), namespace: $namespace, types: $this->types);
    }

    public function __call(string $name, array $arguments): mixed
    {
        if (method_exists(object_or_class: $this->decorated, method: $name)) {
            return $this->decorated->{$name}(...$arguments);
        }

        return null;
    }

    public function getReader(): Reader
    {
        return $this->reader;
    }
}
