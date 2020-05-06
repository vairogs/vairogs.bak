<?php declare(strict_types = 1);

namespace Vairogs\Component\Sitemap\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Vairogs\Component\Utils\DependencyInjection\VairogsConfiguration;

class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        return (new VairogsConfiguration(VairogsSitemapExtension::ALIAS))->getConfiguration($this->getConfiguration());
    }

    private function getConfiguration(): ArrayNodeDefinition
    {
        $node = (new TreeBuilder(VairogsSitemapExtension::ALIAS))->getRootNode();

        // @formatter:off
        $node
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
        ->end();
        // @formatter:on

        return $node;
    }
}
