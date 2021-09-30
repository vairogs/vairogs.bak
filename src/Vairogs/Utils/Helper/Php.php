<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use Closure;
use Doctrine\Common\Annotations\AnnotationReader;
use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use ReflectionClass;
use ReflectionClassConstant;
use ReflectionException;
use ReflectionMethod;
use Vairogs\Utils\Twig\Annotation;
use function array_diff;
use function array_values;
use function class_exists;
use function class_implements;
use function filter_var;
use function get_class_methods;
use function getenv;
use function interface_exists;
use function is_array;
use function is_bool;
use function is_object;
use function method_exists;
use function sprintf;
use function strtolower;
use function trait_exists;
use function ucfirst;

class Php
{
    #[Annotation\TwigFunction]
    public static function hijackSet(object $object, string $property, mixed $value): void
    {
        self::call(function: function () use ($object, $property, $value): void {
            $object->{$property} = $value;
        }, clone: $object);
    }

    /**
     * @noinspection PhpInconsistentReturnPointsInspection
     */
    #[Annotation\TwigFunction]
    public static function call(callable $function, object $clone, bool $return = false): mixed
    {
        $func = Closure::bind(closure: $function, newThis: $clone, newScope: $clone::class);

        if ($return) {
            return $func();
        }

        $func();
    }

    #[Annotation\TwigFilter]
    #[Pure]
    public static function boolval(mixed $value): bool
    {
        if (is_bool(value: $value)) {
            return $value;
        }

        $value = strtolower(string: (string) $value);

        return match ($value) {
            'y' => true,
            'n' => false,
            default => filter_var(value: $value, filter: FILTER_VALIDATE_BOOL),
        };
    }

    /**
     * @throws ReflectionException
     * @throws InvalidArgumentException
     */
    #[Annotation\TwigFunction]
    public static function getClassConstantsValues(string $class): array
    {
        return array_values(array: self::getClassConstants(class: $class));
    }

    /**
     * @throws InvalidArgumentException
     * @throws ReflectionException
     */
    #[Annotation\TwigFunction]
    public static function getClassConstants(string $class): array
    {
        if (self::exists(class: $class)) {
            return (new ReflectionClass(objectOrClass: $class))->getConstants(ReflectionClassConstant::IS_PUBLIC);
        }

        throw new InvalidArgumentException(message: sprintf('Invalid class "%s"', $class));
    }

    #[Annotation\TwigFunction]
    #[Annotation\TwigFilter]
    public static function exists(string $class, $checkTrait = false): bool
    {
        $exists = class_exists(class: $class) || interface_exists(interface: $class);

        if (!$checkTrait) {
            return $exists;
        }

        return $exists || trait_exists(trait: $class);
    }

    #[Annotation\TwigFunction]
    #[Annotation\TwigFilter]
    public static function getParameter(array|object $variable, mixed $key): mixed
    {
        if (is_array(value: $variable)) {
            return $variable[$key];
        }

        if (method_exists(object_or_class: $variable, method: $method = 'get' . ucfirst(string: $key))) {
            return $variable->{$method}();
        }

        return $variable->{$key};
    }

    #[Annotation\TwigFunction]
    #[Annotation\TwigFilter]
    public static function getClassMethods(string $class, ?string $parent = null): array
    {
        $methods = get_class_methods(object_or_class: $class);
        if (null !== $parent) {
            return array_diff(array: $methods, excludes: get_class_methods($parent));
        }

        return $methods;
    }

    /**
     * @throws ReflectionException
     */
    #[Annotation\TwigFilter]
    public static function getShortName(string $class): string
    {
        if (self::exists(class: $class, checkTrait: true)) {
            return (new ReflectionClass(objectOrClass: $class))->getShortName();
        }

        return $class;
    }

    #[Annotation\TwigFunction]
    #[Annotation\TwigFilter]
    public static function classImplements(string $class, string $interface): bool
    {
        return class_exists(class: $class) && interface_exists(interface: $interface) && isset(class_implements(object_or_class: $class)[$interface]);
    }

    #[Annotation\TwigFunction]
    #[Pure]
    public static function getEnv(string $varname, bool $localOnly = false): mixed
    {
        if ($env = getenv(name: $varname, local_only: $localOnly)) {
            return $env;
        }

        return $_ENV[$varname] ?? $varname;
    }

    #[Annotation\TwigFunction]
    #[Annotation\TwigFilter]
    public static function getArray(array|object $input): array
    {
        if (is_object(value: $object = $input)) {
            $input = [];
            foreach ((new ReflectionClass(objectOrClass: $object))->getProperties() as $property) {
                $input[$name = $property->getName()] = self::hijackGet(object: $object, property: $name);
            }
        }

        return $input;
    }

    #[Annotation\TwigFunction]
    public static function hijackGet(object $object, string $property): mixed
    {
        return self::call(function: fn () => $object->{$property}, clone: $object, return: true);
    }

    #[Annotation\TwigFunction]
    #[Annotation\TwigFilter]
    public static function filterExists(ReflectionMethod $method, string $filterClass): bool
    {
        return null !== (new AnnotationReader())->getMethodAnnotation(method: $method, annotationName: $filterClass) || [] !== $method->getAttributes(name: $filterClass);
    }
}
