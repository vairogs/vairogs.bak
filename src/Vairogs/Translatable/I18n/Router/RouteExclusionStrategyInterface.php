<?php declare(strict_types = 1);

namespace Vairogs\Translatable\I18n\Router;

use Symfony\Component\Routing\Route;

interface RouteExclusionStrategyInterface
{
    public function shouldExcludeRoute(string $routeName, Route $route): bool;
}
