<?php declare(strict_types = 1);

namespace Vairogs\Utils\Locator;

use Exception;
use Symfony\Component\Finder\Finder as SymfonyFinder;

class Finder
{
    private SymfonyFinder $finder;
    private array $classMap = [];
    private array $errors = [];

    public function __construct(array $directories, private readonly array $types = [], private readonly string $namesapce = '')
    {
        $this->finder = (new SymfonyFinder())->in(dirs: $directories);
    }

    public function locate(): self
    {
        foreach ($this->finder as $fileInfo) {
            $fileInfo = new SplFileInfo(decorated: $fileInfo, types: $this->types);
            try {
                foreach ($fileInfo->getReader(namespace: $this->namesapce)->getDefinitionNames() as $name) {
                    $this->classMap[$name] = $fileInfo;
                }
            } catch (Exception $exception) {
                $this->errors[] = $exception->getMessage();
            }
        }

        return $this;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getClassMap(): array
    {
        return $this->classMap;
    }
}
