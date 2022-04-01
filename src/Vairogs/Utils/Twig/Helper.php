<?php declare(strict_types = 1);

namespace Vairogs\Utils\Twig;

use Exception;
use ReflectionClass;
use ReflectionMethod;
use Vairogs\Utils\Helper\Php;
use Vairogs\Utils\Helper\Text;

class Helper
{
    public static function getFiltered(string $class, string $filterClass, bool $withClass = true): array
    {
        try {
            $methods = (new ReflectionClass(objectOrClass: $class))->getMethods(filter: ReflectionMethod::IS_PUBLIC);
        } catch (Exception) {
            return [];
        }

        $filtered = [];

        foreach ($methods as $method) {
            if (Php::filterExists(method: $method, filterClass: $filterClass)) {
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
}
