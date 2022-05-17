<?php declare(strict_types = 1);

namespace Vairogs\Twig;

use Exception;
use PhpParser\Node\Stmt\Class_;
use ReflectionClass;
use ReflectionMethod;
use Twig;
use Twig\Extension\AbstractExtension;
use Vairogs\Common\CacheManager;
use Vairogs\Core\Vairogs;
use Vairogs\Extra\Constants\Definition;
use Vairogs\Utils\Helper\Char;
use Vairogs\Utils\Helper\Php;
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
        return $this->getMethods(filter: Attribute\TwigFilter::class, twig: Twig\TwigFilter::class, type: __FUNCTION__);
    }

    public function getFunctions(): array
    {
        return $this->getMethods(filter: Attribute\TwigFunction::class, twig: Twig\TwigFunction::class, type: __FUNCTION__);
    }

    public function getFilteredMethods(string $class, string $filterClass): array
    {
        try {
            $methods = (new ReflectionClass(objectOrClass: $class))->getMethods(filter: ReflectionMethod::IS_PUBLIC);
        } catch (Exception) {
            return [];
        }

        $filtered = [];

        foreach ($methods as $method) {
            if ((new Php())->filterExists(method: $method, filterClass: $filterClass)) {
                $filtered[(new Char())->fromCamelCase(string: $name = $method->getName())] = $this->filter(class: $class, name: $name, isStatic: $method->isStatic());
            }
        }

        return $filtered;
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
            $methods[] = $this->makeArray(input: $this->getFilteredMethods(class: $class, filterClass: $filter), key: $this->getPrefix(base: $class), class: $twig);
        }

        return array_merge(...$methods);
    }

    private function getPrefix(string $base): string
    {
        try {
            $nameSpace = (new ReflectionClass(objectOrClass: $base))->getNamespaceName();
        } catch (Exception) {
            $nameSpace = '\\';
        }

        $short = (new Php())->getShortName(class: $base);

        if (self::HELPER_NAMESPACE === $nameSpace) {
            $base = sprintf('helper_%s', $short);
        } elseif ('Extension' === $short) {
            $base = (new Text())->getLastPart(text: $nameSpace, delimiter: '\\');
        }

        $key = '';
        if (str_starts_with(haystack: $nameSpace, needle: (new Php())->getShortName(class: Vairogs::class))) {
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

    private function filter(string $class, string $name, bool $isStatic = false): array
    {
        if ($isStatic) {
            return [$class, $name, ];
        }

        return [new $class(), $name, ];
    }
}
