<?php declare(strict_types = 1);

namespace Vairogs\Twig;

use PhpParser\Node\Stmt\Class_;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter as TwigTwigFilter;
use Twig\TwigFunction as TwigTwigFunction;
use Vairogs\Cache\CacheManager;
use Vairogs\Core\Attribute\TwigFilter;
use Vairogs\Core\Attribute\TwigFunction;
use Vairogs\Core\Vairogs;
use Vairogs\Extra\Constants\Definition;
use Vairogs\Utils\Helper\Reflection;
use Vairogs\Utils\Locator\Finder;

use function array_keys;
use function array_merge;
use function dirname;
use function hash;
use function sprintf;
use function str_starts_with;
use function strtolower;

final class TwigExtension extends AbstractExtension
{
    use Twig;

    public const HELPER_NAMESPACE = 'Vairogs\Utils\Helper';
    public const HELPER_DIR = '/Utils/Helper/';
    private const CORE_NAMESPACE = 'Vairogs\Core';
    private const CORE_DIR = '/Core/';

    public function __construct(private readonly CacheManager $cacheManager)
    {
    }

    public function getFilters(): array
    {
        return $this->getMethods(filter: TwigFilter::class, twig: TwigTwigFilter::class, type: __FUNCTION__);
    }

    public function getFunctions(): array
    {
        return $this->getMethods(filter: TwigFunction::class, twig: TwigTwigFunction::class, type: __FUNCTION__);
    }

    public function getKey(string $type): string
    {
        return hash(algo: Definition::HASH_ALGORITHM, data: $this->getPrefix(base: $type));
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
        $foundClasses = (new Finder(directories: [dirname(path: __DIR__) . self::HELPER_DIR], types: [Class_::class], namespace: self::HELPER_NAMESPACE))->locate()->getClassMap();
        $foundFunctionsClasses = (new Finder(directories: [dirname(path: __DIR__) . self::CORE_DIR], types: [Class_::class], namespace: self::CORE_DIR))->locate()->getClassMap();

        $foundClasses += $foundFunctionsClasses;

        foreach (array_keys(array: $foundClasses) as $class) {
            $methods[] = $this->makeArray(input: (new Reflection())->getFilteredMethods(class: $class, filterClass: $filter), key: $this->getPrefix(base: $class), class: $twig);
        }

        return array_merge(...$methods);
    }

    private function getPrefix(string $base): string
    {
        $nameSpace = (new Reflection())->getNamespace(class: $base);
        $short = (new Reflection())->getShortName(class: $base);

        if (self::HELPER_NAMESPACE === $nameSpace) {
            $base = sprintf('helper_%s', $short);
        }

        if (self::CORE_NAMESPACE === $nameSpace) {
            $base = sprintf('core_%s', $short);
        }

        $key = '';
        if (str_starts_with(haystack: $nameSpace, needle: (new Reflection())->getShortName(class: Vairogs::class))) {
            $key = Vairogs::VAIROGS;
        }

        if ('' !== $key) {
            $base = sprintf('%s_%s', $key, $base);
        }

        return strtolower(string: $base);
    }
}
