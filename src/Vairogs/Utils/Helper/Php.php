<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use Closure;
use Exception;
use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use ReflectionClass;
use ReflectionClassConstant;
use ReflectionMethod;
use ReflectionProperty;
use RuntimeException;
use Symfony\Component\PropertyAccess\Exception\AccessException;
use Vairogs\Extra\Constants\Definition;
use Vairogs\Extra\Constants\Status;
use Vairogs\Utils\Twig\Attribute;
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
use function property_exists;
use function sprintf;
use function strtolower;
use function trait_exists;
use function ucfirst;
use const FILTER_VALIDATE_BOOL;

final class Php
{
    /**
     * @noinspection StaticClosureCanBeUsedInspection
     */
    #[Attribute\TwigFunction]
    public static function hijackSet(object $object, string $property, mixed $value): object
    {
        self::call(function: function () use ($object, $property, $value): void {
            $object->{$property} = $value;
        }, clone: $object);

        return $object;
    }

    /**
     * @noinspection PhpInconsistentReturnPointsInspection
     */
    #[Attribute\TwigFunction]
    public static function call(callable $function, object $clone, bool $return = false)
    {
        $func = Closure::bind(closure: $function, newThis: $clone, newScope: $clone::class);

        if ($return) {
            return $func();
        }

        $func();
    }

    #[Attribute\TwigFilter]
    #[Pure]
    public static function boolval(mixed $value): bool
    {
        if (is_bool(value: $value)) {
            return $value;
        }

        $value = strtolower(string: (string) $value);

        return match ($value) {
            Status::Y => true,
            Status::N => false,
            default => filter_var(value: $value, filter: FILTER_VALIDATE_BOOL),
        };
    }

    /**
     * @throws RuntimeException
     * @throws InvalidArgumentException
     */
    #[Attribute\TwigFunction]
    public static function getClassConstantsValues(string $class): array
    {
        return array_values(array: self::getClassConstants(class: $class));
    }

    /**
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    #[Attribute\TwigFunction]
    public static function getClassConstants(string $class): array
    {
        if (self::exists(class: $class)) {
            try {
                return (new ReflectionClass(objectOrClass: $class))->getConstants(filter: ReflectionClassConstant::IS_PUBLIC);
            } catch (Exception $e) {
                throw new AccessException(message: $e->getMessage(), code: $e->getCode(), previous: $e);
            }
        }

        throw new InvalidArgumentException(message: sprintf('Invalid class "%s"', $class));
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function exists(string $class, bool $checkTrait = false): bool
    {
        $exists = class_exists(class: $class) || interface_exists(interface: $class);

        if (!$checkTrait) {
            return $exists;
        }

        return $exists || trait_exists(trait: $class);
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function getParameter(array|object $variable, mixed $key): mixed
    {
        if (is_array(value: $variable)) {
            return $variable[$key];
        }

        if (method_exists(object_or_class: $variable, method: $method = Definition::GETTER . ucfirst(string: $key))) {
            return $variable->{$method}();
        }

        return $variable->{$key};
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function getClassMethods(string $class, ?string $parent = null): array
    {
        $methods = get_class_methods(object_or_class: $class);
        if (null !== $parent) {
            return array_diff($methods, get_class_methods(object_or_class: $parent));
        }

        return $methods;
    }

    #[Attribute\TwigFilter]
    public static function getShortName(string $class): string
    {
        if (self::exists(class: $class, checkTrait: true)) {
            try {
                return (new ReflectionClass(objectOrClass: $class))->getShortName();
            } catch (Exception) {
                // exception === can't get short name
            }
        }

        return $class;
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function classImplements(string $class, string $interface): bool
    {
        return class_exists(class: $class) && interface_exists(interface: $interface) && isset(class_implements(object_or_class: $class)[$interface]);
    }

    #[Attribute\TwigFunction]
    public static function getEnv(string $varname, bool $localOnly = false): mixed
    {
        if ($env = getenv($varname, local_only: $localOnly)) {
            return $env;
        }

        return $_ENV[$varname] ?? $varname;
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function getArray(array|object $input): array
    {
        if (is_object(value: $object = $input)) {
            $input = [];
            try {
                foreach ((new ReflectionClass(objectOrClass: $object))->getProperties() as $property) {
                    $input[$name = $property->getName()] = self::hijackGet(object: $object, property: $name);
                }
            } catch (Exception) {
                // exception === not possible to convert object to array
            }
        }

        return $input;
    }

    /**
     * @noinspection StaticClosureCanBeUsedInspection
     *
     * @throws InvalidArgumentException
     */
    #[Attribute\TwigFunction]
    public static function hijackGetStatic(object $object, string $property): mixed
    {
        try {
            if ((new ReflectionProperty(class: $object, property: $property))->isStatic()) {
                return self::call(function: fn () => $object::${$property}, clone: $object, return: true);
            }
        } catch (Exception) {
            // exception === unable to get object property
        }

        throw new InvalidArgumentException(message: sprintf('Property "%s" is not static', $property));
    }

    /**
     * @noinspection StaticClosureCanBeUsedInspection
     *
     * @throws InvalidArgumentException
     */
    #[Attribute\TwigFunction]
    public static function hijackGetNonStatic(object $object, string $property): mixed
    {
        try {
            if ((new ReflectionProperty(class: $object, property: $property))->isStatic()) {
                throw new InvalidArgumentException(message: sprintf('Property "%s" is static', $property));
            }
        } catch (Exception) {
            // exception === unable to get object property
        }

        if (property_exists($object, $property)) {
            return self::call(function: fn () => $object->{$property}, clone: $object, return: true);
        }

        throw new InvalidArgumentException(message: sprintf('Unable to get property "%s" of object %s', $property, $object::class));
    }

    /**
     * @noinspection StaticClosureCanBeUsedInspection
     *
     * @throws InvalidArgumentException
     */
    #[Attribute\TwigFunction]
    public static function hijackGet(object $object, string $property)
    {
        try {
            return self::hijackGetStatic(object: $object, property: $property);
        } catch (Exception) {
            // exception === unable to get object property
        }

        try {
            return self::hijackGetNonStatic(object: $object, property: $property);
        } catch (Exception) {
            // exception === unable to get object property
        }

        throw new InvalidArgumentException(message: sprintf('Unable to get property "%s" of object %s', $property, $object::class));
    }

    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public static function filterExists(ReflectionMethod $method, string $filterClass): bool
    {
        return [] !== $method->getAttributes(name: $filterClass);
    }
}
