<?php declare(strict_types = 1);

namespace Vairogs\Functions;

use Exception;
use InvalidArgumentException;
use ReflectionException;
use ReflectionProperty;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;

use function sprintf;

final class Closure
{
    /**
     * @throws InvalidArgumentException
     */
    public function hijackSet(object $object, string $property, mixed $value): object
    {
        try {
            new ReflectionProperty(class: $object, property: $property);
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

    /**
     * @throws InvalidArgumentException
     */
    public function hijackSetStatic(object $object, string $property, mixed $value): object
    {
        try {
            if ((new ReflectionProperty(class: $object, property: $property))->isStatic()) {
                $this->void(function: function () use ($object, $property, $value): void {
                    $object::${$property} = $value;
                }, clone: $object);

                return $object;
            }
        } catch (Exception) {
            // exception === unable to get object property
        }

        throw new InvalidArgumentException(message: sprintf('Property "%s" is not static', $property));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function hijackSetNonStatic(object $object, string $property, mixed $value): object
    {
        try {
            if ((new ReflectionProperty(class: $object, property: $property))->isStatic()) {
                throw new InvalidArgumentException(message: 'non static property');
            }
        } catch (Exception) {
            throw new InvalidArgumentException(message: sprintf('Property "%s" is static', $property));
        }

        $this->void(function: function () use ($object, $property, $value): void {
            $object->{$property} = $value;
        }, clone: $object);

        return $object;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function hijackGetStatic(object $object, string $property, mixed ...$arguments): mixed
    {
        try {
            if ((new ReflectionProperty(class: $object, property: $property))->isStatic()) {
                return $this->return(fn () => $object::${$property}, $object, ...$arguments);
            }
        } catch (Exception) {
            // exception === unable to get object property
        }

        throw new InvalidArgumentException(message: sprintf('Property "%s" is not static', $property));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function hijackGetNonStatic(object $object, string $property, mixed ...$arguments): mixed
    {
        try {
            if ((new ReflectionProperty(class: $object, property: $property))->isStatic()) {
                throw new InvalidArgumentException(message: 'non static property');
            }
        } catch (Exception) {
            throw new InvalidArgumentException(message: sprintf('Property "%s" is static', $property));
        }

        return $this->return(fn () => $object->{$property}, $object, ...$arguments);
    }

    /**
     * @throws InvalidArgumentException
     * @throws InvalidPropertyPathException
     */
    public function hijackGet(object $object, string $property, bool $throwOnUnInitialized = false, mixed ...$arguments)
    {
        try {
            $reflectionProperty = (new ReflectionProperty(class: $object, property: $property));
        } catch (ReflectionException) {
            throw new InvalidArgumentException(message: sprintf('Unable to get property "%s" of object %s', $property, $object::class));
        }

        if (!$reflectionProperty->isInitialized(object: $object)) {
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

    public function hijackVoid(string $function, ...$arguments): void
    {
        $function(...$arguments);
    }

    public function hijackVoidObject(object $object, string $function, mixed ...$arguments): void
    {
        $this->void(fn () => $object->{$function}(...$arguments), $object, ...$arguments);
    }

    public function hijackReturn(string $function, ...$arguments): mixed
    {
        return $function(...$arguments);
    }

    public function hijackReturnObject(object $object, string $function, mixed ...$arguments): mixed
    {
        return $this->return(fn () => $object->{$function}(...$arguments), $object, ...$arguments);
    }

    private function return(callable $function, object $clone, mixed ...$arguments): mixed
    {
        return $this->bind(function: $function, clone: $clone)(...$arguments);
    }

    private function void(callable $function, object $clone, mixed ...$arguments): void
    {
        $this->bind(function: $function, clone: $clone)(...$arguments);
    }

    private function bind(callable $function, object $clone): ?\Closure
    {
        return \Closure::bind(closure: $function, newThis: $clone, newScope: $clone::class);
    }
}
