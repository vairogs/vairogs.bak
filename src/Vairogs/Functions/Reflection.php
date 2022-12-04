<?php declare(strict_types = 1);

namespace Vairogs\Functions;

use Exception;
use ReflectionClass;
use ReflectionMethod;

final class Reflection
{
    public function attributeExists(ReflectionMethod $reflectionMethod, string $filterClass): bool
    {
        return [] !== $reflectionMethod->getAttributes(name: $filterClass);
    }

    public function getFilteredMethods(string $class, string $filterClass): array
    {
        try {
            $methods = (new ReflectionClass(objectOrClass: $class))->getMethods(filter: ReflectionMethod::IS_PUBLIC);
        } catch (Exception) {
            return [];
        }

        $filtered = [];

        foreach ($methods as $method) {
            if ($this->attributeExists(reflectionMethod: $method, filterClass: $filterClass)) {
                $filtered[(new Char())->fromCamelCase(string: $name = $method->getName())] = $this->filter(class: $class, name: $name, isStatic: $method->isStatic());
            }
        }

        return $filtered;
    }

    public function filter(string $class, string $name, bool $isStatic = false): array
    {
        if ($isStatic) {
            return [$class, $name, ];
        }

        return [new $class(), $name, ];
    }

    public function getNamespace(string $class): string
    {
        try {
            return (new ReflectionClass(objectOrClass: $class))->getNamespaceName();
        } catch (Exception) {
            return '\\';
        }
    }

    public function getShortName(string $class): string
    {
        try {
            return (new ReflectionClass(objectOrClass: $class))->getShortName();
        } catch (Exception) {
            // exception === can't get short name
        }

        return $class;
    }
}
