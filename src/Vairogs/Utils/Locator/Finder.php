<?php declare(strict_types = 1);

namespace Vairogs\Utils\Locator;

use Exception;
use ReflectionClass;
use ReflectionException;
use RuntimeException;
use Symfony\Component\Finder\Finder as SymfonyFinder;

class Finder
{
    private SymfonyFinder $finder;
    private array $classMap = [];
    private array $errors = [];
    private Loader $loader;

    public function __construct(array $directories, private readonly string $namesapce = '')
    {
        $this->finder = (new SymfonyFinder())->in(dirs: $directories);
    }

    public function __destruct()
    {
        $this->disableAutoloading();
    }

    public function locate(): self
    {
        foreach ($this->finder as $fileInfo) {
            $fileInfo = new SplFileInfo(decorated: $fileInfo);
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

    public function enableAutoloading(): void
    {
        $this->loader = new Loader(finder: $this, register: true);
    }

    public function disableAutoloading(): void
    {
        if (isset($this->loader)) {
            $this->loader->unregister();
            unset($this->loader);
        }
    }

    public function getIterator(): iterable
    {
        /** @var SplFileInfo $fileInfo */
        foreach ($this->getClassMap() as $name => $fileInfo) {
            try {
                yield $name => new ReflectionClass(objectOrClass: $name);
            } catch (ReflectionException $e) {
                throw new RuntimeException(message: "Unable to iterate, {$e->getMessage()}, is autoloading enabled?", previous: $e);
            }
        }
    }
}
