<?php declare(strict_types = 1);

namespace Vairogs\Component\Sitemap\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Vairogs\Component\Utils\Vairogs;

class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder(Vairogs::ALIAS);
        $tree = $treeBuilder->getRootNode();

        $tree->children()
            ->arrayNode(VairogsSitemapExtension::ALIAS)
            ->children();

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

        $tree->append($node);
        $tree->end();

        return $treeBuilder;
    }
}
