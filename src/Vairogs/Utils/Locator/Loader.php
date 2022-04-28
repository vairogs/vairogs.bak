<?php declare(strict_types = 1);

namespace Vairogs\Utils\Locator;

class Loader
{
    private array $classMap;

    public function __construct(Finder $finder, bool $register = true)
    {
        $this->classMap = $finder->getClassMap();
        if ($register) {
            $this->register();
        }
    }

    public function register(): bool
    {
        return spl_autoload_register(callback: [$this, 'load']);
    }

    public function unregister(): bool
    {
        return spl_autoload_unregister(callback: [$this, 'load']);
    }

    public function load(string $classname): void
    {
        if (isset($this->classMap[$classname])) {
            require $this->classMap[$classname]->getRealPath();
        }
    }
}
