<?php declare(strict_types = 1);

namespace Vairogs\Utils\Twig;

use InvalidArgumentException;
use Twig\TwigFilter;
use Twig\TwigFunction;
use function in_array;
use function is_array;
use function sprintf;

trait TwigTrait
{
    public function makeArray(array $input, string $key, string $class): array
    {
        if (!in_array(needle: $class, haystack: [
            TwigFilter::class,
            TwigFunction::class,
        ], strict: true)) {
            throw new InvalidArgumentException(message: sprintf('Invalid type "%s":. Allowed types are filter and function', $class));
        }

        $output = [];
        $this->makeInput(input: $input, key: $key, output: $input);

        foreach ($input as $call => $function) {
            if (is_array(value: $function)) {
                $options = $function[2] ?? [];
                unset($function[2]);
                $output[] = new $class($call, $function, $options);
            } else {
                $output[] = new $class($call, [
                    $this,
                    $function,
                ]);
            }
        }

        return $output;
    }

    private function makeInput(array $input, string $key, array &$output): void
    {
        $output = [];

        foreach ($input as $call => $function) {
            $output[sprintf('%s_%s', $key, $call)] = $function;
        }
    }
}