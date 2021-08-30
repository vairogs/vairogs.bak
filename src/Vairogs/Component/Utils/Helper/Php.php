<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Helper;

use Closure;
use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use ReflectionClass;
use ReflectionException;
use Vairogs\Component\Utils\Twig\Annotation;
use function array_diff;
use function array_values;
use function class_exists;
use function class_implements;
use function filter_var;
use function get_class_methods;
use function interface_exists;
use function is_array;
use function is_bool;
use function method_exists;
use function sprintf;
use function strtolower;
use function trait_exists;
use function ucfirst;

class Php
{
    /**
     * @Annotation\TwigFunction()
     */
    public static function hijackSet(object $object, string $property, mixed $value): void
    {
        self::call(function () use ($object, $property, $value): void {
            $object->$property = $value;
        }, $object);
    }

    /**
     * @noinspection PhpInconsistentReturnPointsInspection
     * @Annotation\TwigFunction()
     */
    public static function call(callable $function, object $clone, bool $return = false): mixed
    {
        $func = Closure::bind($function, $clone, $clone::class);

        if ($return) {
            return $func();
        }

        $func();
    }

    /**
     * @Annotation\TwigFunction()
     */
    public static function hijackGet(object $object, string $property): mixed
    {
        return self::call(fn () => $object->$property, $object, true);
    }

    /**
     * @Annotation\TwigFilter()
     */
    #[Pure]
    public static function boolval(mixed $value): bool
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
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @Annotation\TwigFunction()
     */
    public static function getClassConstantsValues(string $class): array
    {
        return array_values(self::getClassConstants($class));
    }

    /**
     * @throws InvalidArgumentException
     * @throws ReflectionException
     * @Annotation\TwigFunction()
     */
    public static function getClassConstants(string $class): array
    {
        if (self::exists($class, true)) {
            return (new ReflectionClass($class))->getConstants();
        }

        throw new InvalidArgumentException(sprintf('Invalid class "%s"', $class));
    }

    /**
     * @Annotation\TwigFilter()
     * @Annotation\TwigFunction()
     */
    public static function exists(string $class, $doNotCheckTrait = false): bool
    {
        if (true === $doNotCheckTrait) {
            return class_exists($class) || interface_exists($class);
        }

        return class_exists($class) || interface_exists($class) || trait_exists($class);
    }

    /**
     * @Annotation\TwigFilter()
     * @Annotation\TwigFunction()
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

    /**
     * @Annotation\TwigFilter()
     * @Annotation\TwigFunction()
     */
    public static function getClassMethods(string $class, ?string $parent = null): array
    {
        $methods = get_class_methods($class);
        if (null !== $parent) {
            return array_diff($methods, get_class_methods($parent));
        }

        return $methods;
    }

    /**
     * @throws ReflectionException
     * @Annotation\TwigFilter()
     */
    public static function getShortName(string $class): string
    {
        if (self::exists($class)) {
            return (new ReflectionClass($class))->getShortName();
        }

        return $class;
    }

    /**
     * @Annotation\TwigFilter()
     * @Annotation\TwigFunction()
     */
    public static function classImplements(string $class, string $interface): bool
    {
        $interfaces = class_implements($class);

        return isset($interfaces[$interface]);
    }
}
