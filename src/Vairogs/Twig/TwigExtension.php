<?php declare(strict_types = 1);

namespace Vairogs\Twig;

use PhpParser\Node\Stmt\Class_;
use Twig;
use Twig\Extension\AbstractExtension;
use Vairogs\Common\CacheManager;
use Vairogs\Core\Vairogs;
use Vairogs\Extra\Constants\Definition;
use Vairogs\Twig\Attribute\TwigFilter;
use Vairogs\Twig\Attribute\TwigFunction;
use Vairogs\Utils\Helper\Reflection;
use Vairogs\Utils\Helper\Text;
use Vairogs\Utils\Locator\Finder;
use function array_keys;
use function array_merge;
use function getcwd;
use function hash;
use function sprintf;
use function str_starts_with;
use function strtolower;

final class TwigExtension extends AbstractExtension
{
    use TwigTrait;

    public const HELPER_NAMESPACE = 'Vairogs\Utils\Helper';
    public const HELPER_DIR = '/../vendor/vairogs/vairogs/src/Vairogs/Utils/Helper/';

    public function __construct(private readonly CacheManager $manager)
    {
    }

    public function getFilters(): array
    {
        return $this->getMethods(filter: TwigFilter::class, twig: Twig\TwigFilter::class, type: __FUNCTION__);
    }

    public function getFunctions(): array
    {
        return $this->getMethods(filter: TwigFunction::class, twig: Twig\TwigFunction::class, type: __FUNCTION__);
    }

    private function getMethods(string $filter, string $twig, string $type): array
    {
        if ([] !== $methods = $this->getMethodCache(type: $type)) {
            return $methods;
        }

        $methods = $this->parseMethods(filter: $filter, twig: $twig);
        $this->manager->set(key: $this->getKey(type: $type), value: $methods);

        return $methods;
    }

    private function getMethodCache(string $type): array
    {
        if ([] !== $methods = ($this->manager->get(key: $this->getKey(type: $type)) ?? [])) {
            return $methods;
        }

        return [];
    }

    private function parseMethods(string $filter, string $twig): array
    {
        $methods = [[]];
        $foundClasses = (new Finder(directories: [getcwd() . self::HELPER_DIR], types: [Class_::class], namespace: self::HELPER_NAMESPACE))->locate()->getClassMap();

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
        } elseif ('Extension' === $short) {
            $base = (new Text())->getLastPart(text: $nameSpace, delimiter: '\\');
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

    private function getKey(string $type): string
    {
        return hash(algo: Definition::HASH_ALGORITHM, data: $this->getPrefix(base: $type));
    }
}
