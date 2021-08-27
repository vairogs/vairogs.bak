<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Twig;

use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Vairogs\Component\Utils\Helper\Text;

class Helper
{
    /**
     * @throws ReflectionException
     */
    public static function getFilterAnnotations(string $class, string $filterClass, bool $withClass = true): array
    {
        $annotationReader = new AnnotationReader();
        $methods = (new ReflectionClass($class))->getMethods(ReflectionMethod::IS_PUBLIC);
        $filtered = [];
        foreach ($methods as $method) {
            if (null !== $annotationReader->getMethodAnnotation($method, $filterClass)) {
                if (true === $withClass) {
                    $filtered[Text::fromCamelCase($method->getName())] = [$class, $method->getName()];
                } else {
                    $filtered[Text::fromCamelCase($method->getName())] = $method->getName();
                }
            }
        }

        return $filtered;
    }
}
