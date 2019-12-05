<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Helper;

use Closure;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use function array_values;
use function class_exists;
use function filter_var;
use function get_class;
use function interface_exists;
use function is_bool;
use function sprintf;
use function strtolower;
use const false;
use const FILTER_VALIDATE_BOOLEAN;
use const true;

class Php
{
    /**
     * @param object $object
     * @param string $property
     * @param $value
     */
    public static function hijackSet(object $object, string $property, $value): void
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
     */
    public static function call(callable $function, object $clone, bool $return = false)
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
     */
    public static function hijackGet(object $object, string $property)
    {
        return self::call(function () use ($object, $property) {
            return $object->$property;
        }, $object, true);
    }

    /**
     * @param $value
     *
     * @return bool
     */
    public static function boolval($value): bool
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

        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
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
}
