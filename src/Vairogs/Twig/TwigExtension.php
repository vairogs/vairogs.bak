<?php declare(strict_types = 1);

namespace Vairogs\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Vairogs\Cache\CacheManager;
use Vairogs\Functions\Constants\Definition;
use Vairogs\Functions\Reflection;

use function array_merge;
use function hash;
use function str_replace;
use function strtolower;

class TwigExtension extends AbstractExtension
{
    use Twig;

    private array $foundClasses = [];

    public function __construct(
        private readonly CacheManager $cacheManager,
        private readonly bool $enabled = false,
        private readonly array $classes = [],
    ) {
    }

    public function getFilters(): array
    {
        return $this->enabled ? $this->getMethods(twig: TwigFilter::class, type: __FUNCTION__) : [];
    }

    public function getFunctions(): array
    {
        return $this->enabled ? $this->getMethods(twig: TwigFunction::class, type: __FUNCTION__) : [];
    }

    public function getKey(string $type): string
    {
        return hash(algo: Definition::HASH_ALGORITHM, data: $this->getPrefix(base: $type));
    }

    private function getMethods(string $twig, string $type): array
    {
        if ([] !== $methods = $this->getMethodCache(type: $type)) {
            return $methods;
        }

        $methods = $this->parseMethods($twig);
        $this->cacheManager->set(key: $this->getKey(type: $type), value: $methods);

        return $methods;
    }

    private function getPrefix(string $base): string
    {
        return strtolower(string: str_replace(search: '\\', replace: '_', subject: $base));
    }

    private function getMethodCache(string $type): array
    {
        if ([] !== $methods = ($this->cacheManager->get(key: $this->getKey(type: $type)) ?? [])) {
            return $methods;
        }

        return [];
    }

    private function parseMethods(string $twig): array
    {
        $methods = [[]];

        foreach ($this->classes as $class) {
            $methods[] = $this->makeArray(input: (new Reflection())->getFilteredMethods(class: $class), key: $this->getPrefix(base: $class), class: $twig);
        }

        return array_merge(...$methods);
    }
}
