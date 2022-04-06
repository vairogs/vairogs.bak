<?php declare(strict_types = 1);

namespace Vairogs\Twig\Php;

use JetBrains\PhpStorm\Pure;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Vairogs\Core\Vairogs;
use function array_keys;
use function array_map;
use function current;
use function is_array;
use function is_numeric;
use function key;
use function sprintf;

class Extension extends AbstractExtension
{
    public function __construct(private readonly array $functions = [], private readonly array $filters = [])
    {
    }

    public function getFilters(): array
    {
        $callbacks = $this->getCallbacks(callables: $this->filters);

        $mappedFilters = array_map(static fn (string $function, callable $callback) => new TwigFilter(name: $function, callable: $callback), array_keys($callbacks), $callbacks);
        $mappedFilters[] = new TwigFilter(name: sprintf('%s_filter', Vairogs::VAIROGS), callable: fn (mixed $object, string $filter, array ...$arguments): mixed => $this->getFilter(object: $object, filter: $filter, arguments: $arguments));

        return $mappedFilters;
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

        $mappedFunctions = array_map(static fn (string $function, callable $callback) => new TwigFunction(name: $function, callable: $callback), array_keys($callbacks), $callbacks);
        $mappedFunctions[] = new TwigFunction(name: sprintf('%s_function', Vairogs::VAIROGS), callable: fn (string $function, array ...$arguments): mixed => $this->getFunction(function: $function, arguments: $arguments));

        return $mappedFunctions;
    }

    /**
     * @param array $arguments
     */
    public function getFunction(string $function, ...$arguments): mixed
    {
        return $function(...$arguments);
    }

    /**
     * @param callable[] $callables
     */
    #[Pure]
    private function getCallbacks(array $callables = []): array
    {
        $callbacks = [];
        foreach ($callables as $function) {
            if (is_array(value: $function) && !is_numeric(value: key(array: $function))) {
                $callback = current(array: $function);
                $function = key(array: $function);
            } else {
                $callback = $function;
            }

            $callbacks[$function] = $callback;
        }

        return $callbacks;
    }
}
