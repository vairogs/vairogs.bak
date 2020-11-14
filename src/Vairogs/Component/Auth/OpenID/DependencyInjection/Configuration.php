<?php declare(strict_types = 1);

namespace Vairogs\Component\Auth\OpenID\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Vairogs\Component\Auth\OpenID\OpenID;
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
        $node = (new TreeBuilder(OpenID::OPENID_BASE_ALIAS))->getRootNode();

        // @formatter:off
        $node
            ->canBeEnabled()
            ->addDefaultsIfNotSet()
            ->children()
                ->append($this->getConfiguration())
            ->end()
        ->end();
        // @formatter:on

        return $node;
    }

    /**
     * @return ArrayNodeDefinition
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     */
    private function getConfiguration(): ArrayNodeDefinition
    {
        $node = (new TreeBuilder(OpenID::ALIAS))->getRootNode();

        // @formatter:off
        $node
            ->canBeEnabled()
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('clients')
                    ->prototype('array')
                        ->prototype('variable')->end()
                    ->end()
                ->end()
            ->end()
        ->end();
        // @formatter:on

        return $node;
    }

    /**
     * @param ArrayNodeDefinition $node
     * @noinspection NullPointerExceptionInspection
     */
    public function buildClientConfiguration(ArrayNodeDefinition $node): void
    {
        $node->addDefaultsIfNotSet();
        $optionsNode = $node->children();

        // @formatter:off
        $optionsNode
            ->scalarNode('api_key')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('openid_url')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('preg_check')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('ns_mode')->defaultValue('sreg')->end()
            ->scalarNode('user_builder')->isRequired()->end()
            ->scalarNode('user_class')->defaultNull()->end()
            ->scalarNode('redirect_route')->isRequired()->cannotBeEmpty()->end()
            ->arrayNode('provider_options')->prototype('variable')->end()->end();
        // @formatter:on

        $optionsNode->end();
    }
}
