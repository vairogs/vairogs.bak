<?php declare(strict_types = 1);

namespace Vairogs\Utils\Twig;

use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Vairogs\Utils\Helper\Text;

class Helper
{
    /**
     * @throws ReflectionException
     */
    public static function getFiltered(string $class, string $filterClass, bool $withClass = true): array
    {
        $methods = (new ReflectionClass(objectOrClass: $class))->getMethods(filter: ReflectionMethod::IS_PUBLIC);
        $filtered = [];

        foreach ($methods as $method) {
            if (self::filterExists(method: $method, filterClass: $filterClass)) {
                if ($withClass) {
                    $filtered[Text::fromCamelCase(string: $method->getName())] = [
                        $class,
                        $method->getName(),
                    ];
                } else {
                    $filtered[Text::fromCamelCase(string: $method->getName())] = $method->getName();
                }
            }
        }

        return $filtered;
    }

    private static function filterExists(ReflectionMethod $method, string $filterClass): bool
    {
        return null !== (new AnnotationReader())->getMethodAnnotation(method: $method, annotationName: $filterClass) || [] !== $method->getAttributes(name: $filterClass);
    }
}
