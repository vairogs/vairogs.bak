<?php declare(strict_types = 1);

namespace Vairogs\Twig;

use PhpParser\Node\Stmt\Class_;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Vairogs\Cache\CacheManager;
use Vairogs\Core\Attribute\CoreFilter;
use Vairogs\Core\Attribute\CoreFunction;
use Vairogs\Functions\Constants\Definition;
use Vairogs\Functions\Reflection;
use Vairogs\Utils\Locator\Finder;

use function array_keys;
use function array_merge;
use function dirname;
use function hash;
use function str_replace;
use function strtolower;

final class TwigExtension extends AbstractExtension
{
    use Twig;

    private array $foundClasses = [];

    public function __construct(private readonly CacheManager $cacheManager)
    {
    }

    public function getFilters(): array
    {
        return $this->getMethods(filter: CoreFilter::class, twig: TwigFilter::class, type: __FUNCTION__);
    }

    public function getFunctions(): array
    {
        return $this->getMethods(filter: CoreFunction::class, twig: TwigFunction::class, type: __FUNCTION__);
    }

    public function getKey(string $type): string
    {
        return hash(algo: Definition::HASH_ALGORITHM, data: $this->getPrefix(base: $type));
    }

    public function getFoundClasses(): array
    {
        if ([] !== $this->foundClasses) {
            return $this->foundClasses;
        }

        return $this->foundClasses = (new Finder(directories: [dirname(path: __DIR__, levels: 4)], types: [Class_::class], ))->locate()->getClassMap();
    }

    private function getMethods(string $filter, string $twig, string $type): array
    {
        if ([] !== $methods = $this->getMethodCache(type: $type)) {
            return $methods;
        }

        $methods = $this->parseMethods(filter: $filter, twig: $twig);
        $this->cacheManager->set(key: $this->getKey(type: $type), value: $methods);

        return $methods;
    }

    private function getMethodCache(string $type): array
    {
        if ([] !== $methods = ($this->cacheManager->get(key: $this->getKey(type: $type)) ?? [])) {
            return $methods;
        }

        return [];
    }

    private function parseMethods(string $filter, string $twig): array
    {
        $methods = [[]];

        foreach (array_keys(array: $this->getFoundClasses()) as $class) {
            $methods[] = $this->makeArray(input: (new Reflection())->getFilteredMethods(class: $class, filterClass: $filter), key: $this->getPrefix(base: $class), class: $twig);
        }

        return array_merge(...$methods);
    }

    private function getPrefix(string $base): string
    {
        return strtolower(string: str_replace(search: '\\', replace: '_', subject: $base));
    }
}
