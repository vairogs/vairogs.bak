<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use Exception;
use Throwable;
use function get_resource_type;
use function implode;
use function is_array;
use function is_bool;
use function is_object;
use function is_resource;
use function is_string;
use function sprintf;

final class Error
{
    public function getExceptionTraceAsString(Throwable|Exception $exception): string
    {
        $rtn = '';
        $count = 0;

        foreach ($exception->getTrace() as $frame) {
            $args = '';
            if (isset($frame['args'])) {
                $arguments = [];
                foreach ($frame['args'] as $arg) {
                    $arguments[] = match (true) {
                        is_array(value: $arg) => 'Array',
                        is_bool(value: $arg) => $arg ? 'true' : 'false',
                        is_object(value: $arg) => $arg::class,
                        is_resource(value: $arg) => get_resource_type(resource: $arg),
                        is_string(value: $arg) => "'" . $arg . "'",
                        null === $arg => 'NULL',
                        default => $arg,
                    };
                }
                $args = implode(separator: ', ', array: $arguments);
            }
            $rtn .= sprintf(
                "#%s %s(%s): %s%s%s(%s)\n",
                $count,
                $frame['file'],
                $frame['line'],
                $frame['class'] ?? '',
                $frame['type'] ?? '',
                $frame['function'],
                $args,
            );
            $count++;
        }

        return $rtn;
    }
}
