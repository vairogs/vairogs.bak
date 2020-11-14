<?php declare(strict_types = 1);

namespace Vairogs\Component\Cache\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Vairogs\Component\Cache\Cache;
use Vairogs\Component\Utils\DependencyInjection\VairogsConfiguration;

class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        return (new VairogsConfiguration())->getConfiguration($this->getBaseConfiguration());
    }

    /**
     * @return ArrayNodeDefinition
     */
    private function getBaseConfiguration(): ArrayNodeDefinition
    {
        $node = (new TreeBuilder(Cache::ALIAS))->getRootNode();

        // @formatter:off
        $node
            ->canBeEnabled()
        ->end();
        // @formatter:on

        return $node;
    }
}
