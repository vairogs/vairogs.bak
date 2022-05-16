<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use Exception;
use Throwable;
use function implode;
use function is_object;
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
                    if (is_object($arg)) {
                        $arguments[] = $arg::class;
                    }
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
