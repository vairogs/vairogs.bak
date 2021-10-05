<?php declare(strict_types = 1);

namespace Vairogs\Translatable\I18n\Router;

use Symfony\Component\Routing\Route;
use Vairogs\Utils\Helper\Php;

class DefaultRouteExclusionStrategy implements RouteExclusionStrategyInterface
{
    public function shouldExcludeRoute(string $routeName, Route $route): bool
    {
        if ('_' === $routeName[0]) {
            return true;
        }

        return false === Php::boolval(value: $route->getOption(name: 'i18n'));
    }
}
