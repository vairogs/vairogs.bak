<?php declare(strict_types = 1);

namespace Vairogs\Twig\Php;

use JetBrains\PhpStorm\Pure;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Vairogs\Component\Utils\Vairogs;
use function array_keys;
use function array_map;
use function current;
use function is_array;
use function is_numeric;
use function key;
use function sprintf;

class Extension extends AbstractExtension
{
    public function __construct(private array $functions = [], private array $filters = [])
    {
    }

    public function getFilters(): array
    {
        $callbacks = $this->getCallbacks($this->filters);

        $mappedFilters = array_map(static fn(string $function, callable $callback) => new TwigFilter($function, $callback), array_keys($callbacks), $callbacks);
        $mappedFilters[] = new TwigFilter(sprintf('%s_filter', Vairogs::VAIROGS), fn(mixed $object, string $filter, array...$arguments): mixed => $this->getFilter($object, $filter, $arguments));

        return $mappedFilters;
    }

    /**
     * @param callable[] $callables
     */
    #[Pure]
    private function getCallbacks(array $callables = []): array
    {
        $callbacks = [];
        foreach ($callables as $function) {
            if (is_array($function) && !is_numeric(key($function))) {
                $callback = current($function);
                $function = key($function);
            } else {
                $callback = $function;
            }

            $callbacks[$function] = $callback;
        }

        return $callbacks;
    }

    /**
     * @param array $arguments
     */
    public function getFilter(mixed $object, string $filter, ...$arguments): mixed
    {
        if ([] === $arguments) {
            return $filter($object);
        }

        return $filter($object, ...$arguments);
    }

    public function getFunctions(): array
    {
        $callbacks = $this->getCallbacks($this->functions);

        $mappedFunctions = array_map(static fn(string $function, callable $callback) => new TwigFunction($function, $callback), array_keys($callbacks), $callbacks);
        $mappedFunctions[] = new TwigFunction(sprintf('%s_function', Vairogs::VAIROGS), fn(string $function, array...$arguments): mixed => $this->getFunction($function, $arguments));

        return $mappedFunctions;
    }

    /**
     * @param array $arguments
     */
    public function getFunction(string $function, ...$arguments): mixed
    {
        return $function(...$arguments);
    }
}
