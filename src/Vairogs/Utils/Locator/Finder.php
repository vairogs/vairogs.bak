<?php declare(strict_types = 1);

namespace Vairogs\Utils\Locator;

use Symfony\Component\Finder\Finder as SymfonyFinder;

class Finder
{
    private readonly SymfonyFinder $finder;
    private array $classMap = [];

    public function __construct(array $directories, private readonly array $types = [], private readonly string $namespace = '', array $notPath = ['vendor', 'var', 'tests'])
    {
        $this->finder = (new SymfonyFinder())
            ->in(dirs: $directories)
            ->notPath(patterns: $notPath);
    }

    public function locate(): self
    {
        foreach ($this->finder as $file) {
            $fileInfo = new SplFileInfo(decorated: $file, types: $this->types, namespace: $this->namespace);

            foreach ($fileInfo->getReader()->getDefinitionNames() as $name) {
                $this->classMap[$name] = $fileInfo;
            }
        }

        return $this;
    }

    public function getClassMap(): array
    {
        return $this->classMap;
    }

    public function getClass(string $class): ?object
    {
        return $this->classMap[$class] ?? null;
    }
}
