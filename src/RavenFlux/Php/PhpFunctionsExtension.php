<?php

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
    /**
     * @var array
     */
    private $functions;

    /**
     * @var array
     */
    private $filters;

    /**
     * @param null|array $functions
     * @param null|array $filters
     */
    public function __construct($functions = null, $filters = null)
    {
        $this->functions = $functions ?: [];
        $this->filters = $filters ?: [];
    }

    /**
     * @return array
     */
    public function getFilters(): array
    {
        $callbacks = $this->getCallbacks($this->filters);

        $filters = array_map(function ($function, $callback) {
            return new TwigFilter($function, $callback);
        }, array_keys($callbacks), $callbacks);

        $filters[] = new TwigFilter('raven_filter', [$this, 'ravenFilter']);

        return $filters;
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

        $functions = array_map(function ($function, $callback) {
            return new TwigFunction($function, $callback);
        }, array_keys($callbacks), $callbacks);

        $functions[] = new TwigFunction('raven_function', [$this, 'ravenFunction']);

        return $functions;
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
