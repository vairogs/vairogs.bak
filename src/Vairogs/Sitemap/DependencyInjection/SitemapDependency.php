<?php declare(strict_types = 1);

namespace Vairogs\Sitemap\DependencyInjection;

use Spaghetti\DependencyInjection\Dependency;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SitemapDependency implements Dependency
{
    public const SITEMAP = 'sitemap';

    /**
     * @noinspection NullPointerExceptionInspection
     */
    public function getConfiguration(ArrayNodeDefinition $arrayNodeDefinition): void
    {
        $arrayNodeDefinition
            ->children()
            ->arrayNode(name: self::SITEMAP)
                ->canBeEnabled()
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode(name: 'host')->defaultValue(value: null)->end()
                    ->scalarNode(name: 'limit')->defaultValue(value: null)->end()
                ->end()
            ->end()
        ->end();
    }

    public function loadComponent(ContainerBuilder $container, ConfigurationInterface $configuration): void
    {
        // Part of interface, not needed in this component
    }
}
