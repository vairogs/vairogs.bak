<?php declare(strict_types = 1);

namespace Vairogs\Utils\Twig;

use InvalidArgumentException;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Vairogs\Utils\Helper\Char;
use function in_array;
use function is_array;
use function is_numeric;
use function sprintf;

trait TwigTrait
{
    /**
     * @throws InvalidArgumentException
     */
    public function makeArray(array $input, string $key, string $class): array
    {
        $this->checkClass(class: $class);

        $functions = [];
        foreach ($input as $call => $function) {
            if (is_numeric(value: $call)) {
                $call = Char::toSnakeCase(string: $function, skipCamel: true);
            }

            $functions[sprintf('%s_%s', $key, $call)] = $function;
        }

        $output = [];
        foreach ($functions as $call => $function) {
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

    /**
     * @throws InvalidArgumentException
     */
    public function checkClass(string $class): void
    {
        if (!in_array(needle: $class, haystack: [
            TwigFilter::class,
            TwigFunction::class,
        ], strict: true)) {
            throw new InvalidArgumentException(message: sprintf('Invalid type "%s":. Allowed types are %s and %s', $class, TwigFilter::class, TwigFunction::class));
        }
    }
}
