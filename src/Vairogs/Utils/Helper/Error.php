<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use Exception;
use Throwable;
use function get_resource_type;
use function implode;
use function is_array;
use function is_object;
use function is_resource;
use function sprintf;

final class Error
{
    public function getExceptionTraceAsString(Throwable|Exception $exception): string
    {
        $result = '';
        $count = 0;

        foreach ($exception->getTrace() as $frame) {
            $arguments = [];
            foreach ($frame['args'] ?? [] as $argument) {
                $arguments[] = match (true) {
                    is_array(value: $argument) => sprintf('[%s]', implode(separator: ', ', array: $argument)),
                    is_object(value: $argument) => $argument::class,
                    is_resource(value: $argument) => get_resource_type(resource: $argument),
                    default => $argument,
                };
            }

            $result .= sprintf(
                "#%s %s(%s): %s%s%s(%s)\n",
                $count++,
                $frame['file'],
                $frame['line'],
                $frame['class'] ?? '',
                $frame['type'] ?? '',
                $frame['function'],
                implode(separator: ', ', array: $arguments),
            );
        }

        return $result;
    }
}
