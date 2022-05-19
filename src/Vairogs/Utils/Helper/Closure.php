<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use Exception;
use InvalidArgumentException;
use ReflectionException;
use ReflectionProperty;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Vairogs\Twig\Attribute;
use function sprintf;

final class Closure
{
    /** @throws InvalidArgumentException */
    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function hijackSet(object $object, string $property, mixed $value): object
    {
        try {
            (new ReflectionProperty(class: $object, property: $property));
        } catch (ReflectionException) {
            throw new InvalidArgumentException(message: sprintf('Unable to set property "%s" of object %s', $property, $object::class));
        }

        try {
            return $this->hijackSetNonStatic(object: $object, property: $property, value: $value);
        } catch (Exception) {
            // exception === unable to get object property
        }

        return $this->hijackSetStatic(object: $object, property: $property, value: $value);
    }

    /** @throws InvalidArgumentException */
    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function hijackSetStatic(object $object, string $property, mixed $value): object
    {
        try {
            if ((new ReflectionProperty(class: $object, property: $property))->isStatic()) {
                $this->call(function: function () use ($object, $property, $value): void {
                    $object::${$property} = $value;
                }, clone: $object);

                return $object;
            }
        } catch (Exception) {
            // exception === unable to get object property
        }

        throw new InvalidArgumentException(message: sprintf('Property "%s" is not static', $property));
    }

    /** @throws InvalidArgumentException */
    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function hijackSetNonStatic(object $object, string $property, mixed $value): object
    {
        try {
            if ((new ReflectionProperty(class: $object, property: $property))->isStatic()) {
                throw new InvalidArgumentException(message: 'non static property');
            }
        } catch (Exception) {
            throw new InvalidArgumentException(message: sprintf('Property "%s" is static', $property));
        }

        $this->call(function: function () use ($object, $property, $value): void {
            $object->{$property} = $value;
        }, clone: $object);

        return $object;
    }

    /**
     * @noinspection PhpInconsistentReturnPointsInspection
     */
    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function call(callable $function, object $clone, bool $return = false, ...$arguments)
    {
        $func = \Closure::bind(closure: $function, newThis: $clone, newScope: $clone::class);

        if ($return) {
            return $func(...$arguments);
        }

        $func(...$arguments);
    }

    /** @throws InvalidArgumentException */
    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function hijackGetStatic(object $object, string $property, ...$arguments): mixed
    {
        try {
            if ((new ReflectionProperty(class: $object, property: $property))->isStatic()) {
                return $this->call(fn () => $object::${$property}, $object, true, ...$arguments);
            }
        } catch (Exception) {
            // exception === unable to get object property
        }

        throw new InvalidArgumentException(message: sprintf('Property "%s" is not static', $property));
    }

    /** @throws InvalidArgumentException */
    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function hijackGetNonStatic(object $object, string $property, ...$arguments): mixed
    {
        try {
            if ((new ReflectionProperty(class: $object, property: $property))->isStatic()) {
                throw new InvalidArgumentException(message: 'non static property');
            }
        } catch (Exception) {
            throw new InvalidArgumentException(message: sprintf('Property "%s" is static', $property));
        }

        return $this->call(fn () => $object->{$property}, $object, true, ...$arguments);
    }

    /**
     * @throws InvalidArgumentException
     * @throws InvalidPropertyPathException
     */
    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function hijackGet(object $object, string $property, bool $throwOnUnInitialized = false, ...$arguments)
    {
        try {
            $objectProperty = (new ReflectionProperty(class: $object, property: $property));
        } catch (ReflectionException) {
            throw new InvalidArgumentException(message: sprintf('Unable to get property "%s" of object %s', $property, $object::class));
        }

        if (!$objectProperty->isInitialized(object: $object)) {
            if ($throwOnUnInitialized) {
                throw new InvalidPropertyPathException(message: sprintf('%s::%s must not be accessed before initialization', $object::class, $property));
            }

            return null;
        }

        try {
            return $this->hijackGetNonStatic($object, $property, ...$arguments);
        } catch (Exception) {
            // exception === unable to get object property
        }

        return $this->hijackGetStatic($object, $property, ...$arguments);
    }

    /** @noinspection PhpInconsistentReturnPointsInspection */
    #[Attribute\TwigFunction]
    #[Attribute\TwigFilter]
    public function hijackCall(?object $object, string $function, bool $return = false, ...$arguments)
    {
        if (null === $object) {
            if ($return) {
                return $function(...$arguments);
            }

            $function(...$arguments);
        } else {
            if ($return) {
                return $this->call(fn () => $object->{$function}(...$arguments), $object, $return, ...$arguments);
            }

            $this->call(fn () => $object->{$function}(...$arguments), $object, $return, ...$arguments);
        }
    }
}
