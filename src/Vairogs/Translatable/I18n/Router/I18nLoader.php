<?php declare(strict_types = 1);

namespace Vairogs\Translatable\I18n\Router;

use Symfony\Component\Routing\RouteCollection;

class I18nLoader
{
    public const ROUTING_PREFIX = '__RG__';

    public function __construct(private RouteExclusionStrategyInterface $routeExclusionStrategy, private PatternGenerationStrategyInterface $patternGenerationStrategy)
    {
    }

    public function load(RouteCollection $collection): RouteCollection
    {
        $i18nCollection = new RouteCollection();
        foreach ($collection->getResources() as $resource) {
            $i18nCollection->addResource(resource: $resource);
        }
        $this->patternGenerationStrategy->addResources(i18NRouteRouteCollection: $i18nCollection);

        foreach ($collection->all() as $name => $route) {
            if ($this->routeExclusionStrategy->shouldExcludeRoute(routeName: $name, route: $route)) {
                $i18nCollection->add(name: $name, route: $route);
                continue;
            }

            foreach ($this->patternGenerationStrategy->generateI18nPatterns(routeName: $name, route: $route) as $pattern => $locales) {
                if (count(value: $locales) > 1) {
                    $catchMultipleRoute = clone $route;
                    $catchMultipleRoute->setPath(pattern: $pattern);
                    $catchMultipleRoute->setDefault(name: '_locales', default: $locales);
                    $i18nCollection->add(name: implode(separator: '_', array: $locales) . self::ROUTING_PREFIX . $name, route: $catchMultipleRoute);
                }

                foreach ($locales as $locale) {
                    $localeRoute = clone $route;
                    $localeRoute->setPath(pattern: $pattern);
                    $localeRoute->setDefault(name: '_locale', default: $locale);
                    $i18nCollection->add(name: $locale . self::ROUTING_PREFIX . $name, route: $localeRoute);
                }
            }
        }

        return $i18nCollection;
    }
}
