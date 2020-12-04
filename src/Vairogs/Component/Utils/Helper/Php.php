<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Helper;

use Closure;
use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use ReflectionClass;
use ReflectionException;
use function array_values;
use function class_exists;
use function filter_var;
use function get_class;
use function interface_exists;
use function is_array;
use function is_bool;
use function method_exists;
use function sprintf;
use function strtolower;
use function ucfirst;
use const FILTER_VALIDATE_BOOL;

class Php
{
    /**
     * @param object $object
     * @param string $property
     * @param mixed $value
     * @noinspection StaticClosureCanBeUsedInspection
     */
    public static function hijackSet(object $object, string $property, mixed $value): void
    {
        self::call(function () use ($object, $property, $value) {
            $object->$property = $value;
        }, $object);
    }

    /**
     * @param callable $function
     * @param object $clone
     * @param bool $return
     *
     * @return mixed
     * @noinspection PhpInconsistentReturnPointsInspection
     */
    public static function call(callable $function, object $clone, bool $return = false): mixed
    {
        $func = Closure::bind($function, $clone, get_class($clone));

        if (true === $return) {
            return $func();
        }

        $func();
    }

    /**
     * @param object $object
     * @param string $property
     *
     * @return mixed
     * @noinspection StaticClosureCanBeUsedInspection
     */
    public static function hijackGet(object $object, string $property): mixed
    {
        return self::call(function () use ($object, $property) {
            return $object->$property;
        }, $object, true);
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    #[Pure] public static function boolval(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        $value = strtolower((string)$value);

        if ('y' === $value) {
            return true;
        }

        if ('n' === $value) {
            return false;
        }

        return filter_var($value, FILTER_VALIDATE_BOOL);
    }

    /**
     * @param string $class
     *
     * @return array
     * @throws ReflectionException
     * @throws InvalidArgumentException
     */
    public static function getClassConstantsValues(string $class): array
    {
        return array_values(self::getClassConstants($class));
    }

    /**
     * @param string $class
     *
     * @return array
     * @throws InvalidArgumentException
     * @throws ReflectionException
     */
    public static function getClassConstants(string $class): array
    {
        if (class_exists($class) || interface_exists($class)) {
            return (new ReflectionClass($class))->getConstants();
        }

        throw new InvalidArgumentException(sprintf('Invalid class "%s"', $class));
    }

    /**
     * @param array|object $variable
     * @param mixed $key
     * @return mixed
     */
    public static function getParameter(object|array $variable, mixed $key): mixed
    {
        if (is_array($variable)) {
            return $variable[$key];
        }

        if (method_exists($variable, 'get' . ucfirst($key))) {
            return $variable->{'get' . ucfirst($key)}();
        }

        return $variable->$key;
    }
}
