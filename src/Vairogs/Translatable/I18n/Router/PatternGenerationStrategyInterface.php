<?php declare(strict_types = 1);

namespace Vairogs\Translatable\I18n\Router;

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

interface PatternGenerationStrategyInterface
{
    public function generateI18nPatterns(string $routeName, Route $route): array;

    public function addResources(RouteCollection $i18NRouteRouteCollection): void;
}
