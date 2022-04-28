<?php declare(strict_types = 1);

namespace Vairogs\Utils\Twig;

use Exception;
use PhpParser\Node\Stmt\Class_;
use Psr\Cache\InvalidArgumentException;
use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\Cache\Adapter\ChainAdapter;
use Twig;
use Twig\Extension\AbstractExtension;
use Vairogs\Common;
use Vairogs\Core\Vairogs;
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
    use Common\Cache;
    use TwigTrait;

    public const HELPER_NAMESPACE = 'Vairogs\Utils\Helper';
    public const HELPER_DIR = '/../vendor/vairogs/vairogs/src/Vairogs/Utils/Helper/';

    private ?ChainAdapter $adapter = null;

    public function __construct(private readonly int $defaultLifetime = Common\Common::DEFAULT_LIFETIME, ...$adapters)
    {
        if ([] !== $adapters) {
            $this->adapter = (new Common\Common())->getChainAdapter(self::class, $this->defaultLifetime, ...$adapters);
        }
    }

    public function getFilters(): array
    {
        return $this->getMethods(filter: Attribute\TwigFilter::class, twig: Twig\TwigFilter::class, type: __FUNCTION__);
    }

    public function getFunctions(): array
    {
        return $this->getMethods(filter: Attribute\TwigFunction::class, twig: Twig\TwigFunction::class, type: __FUNCTION__);
    }

    public static function getFiltered(string $class, string $filterClass, bool $withClass = true): array
    {
        try {
            $methods = (new ReflectionClass(objectOrClass: $class))->getMethods(filter: ReflectionMethod::IS_PUBLIC);
        } catch (Exception) {
            return [];
        }

        $filtered = [];

        foreach ($methods as $method) {
            if (Php::filterExists(method: $method, filterClass: $filterClass)) {
                $filtered[Char::fromCamelCase(string: $name = $method->getName())] = self::getFilter(class: $class, name: $name, withClass: $withClass);
            }
        }

        return $filtered;
    }

    private function getMethods(string $filter, string $twig, string $type): array
    {
        try {
            if (null !== $this->adapter && $methods = $this->getCache(adapter: $this->adapter, key: $this->getKey(type: $type))) {
                return $methods;
            }
        } catch (InvalidArgumentException) {
            // cache not found
        }

        $methods = [[]];
        $spls = (new Finder(directories: [getcwd() . self::HELPER_DIR], types: [Class_::class], namesapce: self::HELPER_NAMESPACE))->locate()->getClassMap();

        foreach (array_keys($spls) as $class) {
            $methods[] = $this->makeArray(input: self::getFiltered(class: $class, filterClass: $filter), key: $this->getPrefix(base: $class), class: $twig);
        }

        $methods = array_merge(...$methods);

        if (null !== $this->adapter) {
            try {
                $this->setCache(adapter: $this->adapter, key: $this->getKey(type: $type), value: $methods, expiresAfter: $this->defaultLifetime);
            } catch (InvalidArgumentException) {
                // don't set cache if exception
            }
        }

        return $methods;
    }

    private function getPrefix(string $base): string
    {
        try {
            $nameSpace = (new ReflectionClass(objectOrClass: $base))->getNamespaceName();
        } catch (Exception) {
            $nameSpace = '\\';
        }

        $short = Php::getShortName(class: $base);

        if (self::HELPER_NAMESPACE === $nameSpace) {
            $base = sprintf('%s_%s', 'helper', $short);
        } elseif ('Extension' === $short) {
            $base = Text::getLastPart(text: $nameSpace, delimiter: '\\');
        }

        $key = '';
        if (str_starts_with(haystack: $nameSpace, needle: Php::getShortName(class: Vairogs::class))) {
            $key = Vairogs::VAIROGS;
        }

        if ('' !== $key) {
            $base = sprintf('%s_%s', $key, $base);
        }

        return strtolower(string: $base);
    }

    private function getKey(string $type): string
    {
        return hash(algo: Common\Common::HASH_ALGORITHM, data: $this->getPrefix($type));
    }

    private static function getFilter(string $class, string $name, bool $withClass = true): string|array
    {
        if ($withClass) {
            return [
                $class,
                $name,
            ];
        }

        return $name;
    }
}
