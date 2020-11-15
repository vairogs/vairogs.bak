<?php declare(strict_types = 1);

namespace Vairogs\Component\Sitemap\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Vairogs\Component\Utils\DependencyInjection\Component;
use Vairogs\Component\Utils\DependencyInjection\Dependency;

class SitemapDependency implements Dependency
{
    /**
     * @inheritDoc
     * @noinspection NullPointerExceptionInspection
     */
    public function getConfiguration(ArrayNodeDefinition $node): void
    {
        // @formatter:off
        $node
            ->children()
            ->arrayNode(Component::SITEMAP)
                ->canBeEnabled()
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('host')
                        ->defaultValue(null)
                    ->end()
                    ->scalarNode('limit')
                        ->defaultValue(null)
                    ->end()
                ->end()
            ->end()
        ->end();
        // @formatter:on
    }

    /**
     * @inheritDoc
     */
    public function loadComponent(ContainerBuilder $container, ConfigurationInterface $configuration): void
    {
    }
}
