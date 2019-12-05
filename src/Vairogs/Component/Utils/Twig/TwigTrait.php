<?php declare(strict_types = 1);

namespace Vairogs\Utils\Twig;

use InvalidArgumentException;
use Twig\TwigFilter;
use Twig\TwigFunction;
use function is_array;
use function sprintf;

trait TwigTrait
{
    /**
     * @param array $input
     * @param string $type
     *
     * @return array
     * @throws InvalidArgumentException
     */
    public function makeArray(array $input, $type = 'filter'): array
    {
        switch ($type) {
            case 'filter':
                $class = TwigFilter::class;
                break;
            case 'function':
                $class = TwigFunction::class;
                break;
            default:
                throw new InvalidArgumentException(sprintf('Invalid type "%s":. Allowed types are filter and function', $type));
        }

        $output = [];
        $this->makeInput($input, $input);
        foreach ($input as $call => $function) {
            if (is_array($function)) {
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

    /**
     * @param array $input
     * @param $output
     */
    private function makeInput(array $input, &$output): void
    {
        $output = [];
        foreach ($input as $call => $function) {
            $output[sprintf('vairogs_%s', $call)] = $function;
        }
    }
}
