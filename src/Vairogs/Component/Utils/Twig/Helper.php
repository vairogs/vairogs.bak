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
     * @param string $class
     * @param string $filterClass
     * @return array
     * @throws ReflectionException
     */
    public static function getTwigAnnotations(string $class, string $filterClass): array
    {
        $reader = new AnnotationReader();
        $methods = (new ReflectionClass($class))->getMethods(ReflectionMethod::IS_PUBLIC);
        $filtered = [];
        foreach ($methods as $method) {
            if (null !== $reader->getMethodAnnotation($method, $filterClass)) {
                $filtered[Text::fromCamelCase($method->getName())] = [$class, $method->getName()];
            }
        }

        return $filtered;
    }
}
