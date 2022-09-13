<?php declare(strict_types = 1);

namespace Vairogs\Twig;

use Vairogs\Utils\Helper\Char;

use function is_array;
use function is_numeric;
use function sprintf;

trait TwigTrait
{
    public function makeArray(array $input, string $key, string $class): array
    {
        $output = [];
        foreach ($this->parseFunctions(input: $input, key: $key) as $call => $function) {
            if (is_array(value: $function)) {
                $options = $function[2] ?? [];
                unset($function[2]);
                $output[] = new $class($call, $function, $options);
                continue;
            }

            $output[] = new $class($call, [$this, $function, ]);
        }

        return $output;
    }

    private function parseFunctions(array $input, string $key): array
    {
        $functions = [];
        foreach ($input as $call => $function) {
            if (is_numeric(value: $call)) {
                $call = (new Char())->toSnakeCase(string: $function, skipCamel: true);
            }

            $functions[sprintf('%s_%s', $key, $call)] = $function;
        }

        return $functions;
    }
}
