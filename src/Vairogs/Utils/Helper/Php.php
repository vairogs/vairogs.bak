<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use Exception;
use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use ReflectionClass;
use ReflectionClassConstant;
use ReflectionObject;
use RuntimeException;
use Symfony\Component\PropertyAccess\Exception\AccessException;
use Vairogs\Core\Attribute\TwigFilter;
use Vairogs\Core\Attribute\TwigFunction;
use Vairogs\Extra\Constants\Status;

use function array_diff;
use function array_unshift;
use function array_values;
use function filter_var;
use function get_class_methods;
use function is_array;
use function is_bool;
use function is_object;
use function sprintf;
use function strtolower;
use function ucfirst;

use const FILTER_VALIDATE_BOOL;

final class Php
{
    #[TwigFunction]
    #[TwigFilter]
    #[Pure]
    public function boolval(mixed $value): bool
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
    #[TwigFunction]
    #[TwigFilter]
    public function getClassConstantsValues(string $class): array
    {
        return array_values(array: $this->getClassConstants(class: $class));
    }

    /**
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    #[TwigFunction]
    #[TwigFilter]
    public function getClassConstants(string $class): array
    {
        try {
            return (new ReflectionClass(objectOrClass: $class))->getConstants(filter: ReflectionClassConstant::IS_PUBLIC);
        } catch (Exception $e) {
            throw new AccessException(message: $e->getMessage(), code: $e->getCode(), previous: $e);
        }
    }

    #[TwigFunction]
    #[TwigFilter]
    public function getParameter(array|object $variable, mixed $key): mixed
    {
        if (is_array(value: $variable)) {
            return $variable[$key];
        }

        return (new Closure())->hijackGet(object: $variable, property: $key);
    }

    #[TwigFunction]
    #[TwigFilter]
    public function getClassMethods(string $class, ?string $parent = null): array
    {
        $methods = get_class_methods(object_or_class: $class);
        if (null !== $parent) {
            return array_diff($methods, get_class_methods(object_or_class: $parent));
        }

        return $methods;
    }

    #[TwigFunction]
    #[TwigFilter]
    public function getArray(array|object $input): array
    {
        if (is_object(value: $input)) {
            return $this->getArrayFromObject($input);
        }

        return $input;
    }

    #[TwigFunction]
    #[TwigFilter]
    public function getArrayFromObject(object $object): array
    {
        $input = [];

        foreach ((new ReflectionObject(object: $object))->getProperties() as $reflectionProperty) {
            $input[$name = $reflectionProperty->getName()] = (new Closure())->hijackGet(object: $object, property: $name);
        }

        return $input;
    }

    #[TwigFunction]
    #[TwigFilter]
    public function getter(string $variable): string
    {
        return sprintf('get%s', ucfirst(string: $variable));
    }

    #[TwigFunction]
    #[TwigFilter]
    public function setter(string $variable): string
    {
        return sprintf('set%s', ucfirst(string: $variable));
    }

    #[TwigFunction]
    #[TwigFilter]
    public function call(mixed $value, string $function, ...$arguments): mixed
    {
        array_unshift($arguments, $value);

        return (new Closure())->hijackReturn($function, ...$arguments);
    }

    #[TwigFunction]
    #[TwigFilter]
    public function callObject(mixed $value, object $object, string $function, ...$arguments): mixed
    {
        array_unshift($arguments, $value);

        return (new Closure())->hijackReturnObject($object, $function, ...$arguments);
    }
}
