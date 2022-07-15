<?php declare(strict_types = 1);

namespace Vairogs\Utils\Helper;

use Exception;
use InvalidArgumentException;
use ReflectionException;
use ReflectionProperty;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Vairogs\Twig\Attribute\TwigFilter;
use Vairogs\Twig\Attribute\TwigFunction;

use function sprintf;

final class Closure
{
    /** @throws InvalidArgumentException */
    #[TwigFunction]
    #[TwigFilter]
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

    /** @throws InvalidArgumentException */
    #[TwigFunction]
    #[TwigFilter]
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

    /** @throws InvalidArgumentException */
    #[TwigFunction]
    #[TwigFilter]
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

    #[TwigFunction]
    #[TwigFilter]
    public function return(callable $function, object $clone, ...$arguments): mixed
    {
        return $this->bind(function: $function, clone: $clone)(...$arguments);
    }

    #[TwigFunction]
    #[TwigFilter]
    public function void(callable $function, object $clone, ...$arguments): void
    {
        $this->bind(function: $function, clone: $clone)(...$arguments);
    }

    /** @throws InvalidArgumentException */
    #[TwigFunction]
    #[TwigFilter]
    public function hijackGetStatic(object $object, string $property, ...$arguments): mixed
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

    /** @throws InvalidArgumentException */
    #[TwigFunction]
    #[TwigFilter]
    public function hijackGetNonStatic(object $object, string $property, ...$arguments): mixed
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
    #[TwigFunction]
    #[TwigFilter]
    public function hijackGet(object $object, string $property, bool $throwOnUnInitialized = false, ...$arguments)
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

    #[TwigFunction]
    #[TwigFilter]
    public function hijackVoid(string $function, ...$arguments): void
    {
        $function(...$arguments);
    }

    #[TwigFunction]
    #[TwigFilter]
    public function hijackVoidObject(object $object, string $function, ...$arguments): void
    {
        $this->void(fn () => $object->{$function}(...$arguments), $object, ...$arguments);
    }

    #[TwigFunction]
    #[TwigFilter]
    public function hijackReturn(string $function, ...$arguments): mixed
    {
        return $function(...$arguments);
    }

    #[TwigFunction]
    #[TwigFilter]
    public function hijackReturnObject(object $object, string $function, ...$arguments): mixed
    {
        return $this->return(fn () => $object->{$function}(...$arguments), $object, ...$arguments);
    }

    private function bind(callable $function, object $clone): \Closure|false|null
    {
        return \Closure::bind(closure: $function, newThis: $clone, newScope: $clone::class);
    }
}
