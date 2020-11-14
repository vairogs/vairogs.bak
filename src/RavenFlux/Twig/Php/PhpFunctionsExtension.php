<?php declare(strict_types = 1);

namespace RavenFlux\Twig\Php;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use function array_keys;
use function array_map;
use function current;
use function is_array;
use function is_numeric;
use function key;

class PhpFunctionsExtension extends AbstractExtension
{
    private ?array $functions;
    private ?array $filters;

    /**
     * @param array $functions
     * @param array $filters
     */
    public function __construct($functions = [], $filters = [])
    {
        $this->functions = $functions;
        $this->filters = $filters;
    }

    /**
     * @return array
     */
    public function getFilters(): array
    {
        $callbacks = $this->getCallbacks($this->filters);

        $mappedFilters = array_map(static function ($function, $callback) {
            return new TwigFilter($function, $callback);
        }, array_keys($callbacks), $callbacks);

        $mappedFilters[] = new TwigFilter('raven_filter', [
            $this,
            'ravenFilter',
        ]);

        return $mappedFilters;
    }

    /**
     * @param array $callables
     *
     * @return array
     */
    private function getCallbacks(array $callables = []): array
    {
        $result = [];
        foreach ($callables as $function) {
            if (is_array($function) && !is_numeric(key($function))) {
                $callback = current($function);
                $function = key($function);
            } else {
                $callback = $function;
            }
            $result[$function] = $callback;
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getFunctions(): array
    {
        $callbacks = $this->getCallbacks($this->functions);

        $mappedFunctions = array_map(static function ($function, $callback) {
            return new TwigFunction($function, $callback);
        }, array_keys($callbacks), $callbacks);

        $mappedFunctions[] = new TwigFunction('raven_function', [
            $this,
            'ravenFunction',
        ]);

        return $mappedFunctions;
    }

    /**
     * @param $object
     * @param string $filter
     * @param mixed ...$arguments
     *
     * @return mixed
     */
    public function ravenFilter($object, string $filter, ...$arguments)
    {
        if (!$arguments) {
            return $filter($object);
        }

        return $filter($object, ...$arguments);
    }

    /**
     * @param string $function
     * @param mixed ...$arguments
     *
     * @return mixed
     */
    public function ravenFunction(string $function, ...$arguments)
    {
        return $function(...$arguments);
    }
}
