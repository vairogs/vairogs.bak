<?php declare(strict_types = 1);

namespace Vairogs\Auth\OpenIDConnect\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Vairogs\Auth\DependencyInjection\AbstractAuthDependency;
use Vairogs\Auth\OpenIDConnect\Configuration\DefaultProvider;
use Vairogs\Component\Utils\DependencyInjection\Component;

class AuthOpenIDConnectDependency extends AbstractAuthDependency
{
    public static function buildClientConfiguration(ArrayNodeDefinition $arrayNodeDefinition): void
    {
        $arrayNodeDefinition->addDefaultsIfNotSet();
        $optionsNode = $arrayNodeDefinition->children();

        // @formatter:off
        $optionsNode
            ->scalarNode(name: 'client_id')->isRequired()->defaultValue(value: null)->end()
            ->scalarNode(name: 'client_secret')->defaultValue(value: null)->end()
            ->scalarNode(name: 'id_token_issuer')->isRequired()->defaultValue(value: null)->end()
            ->scalarNode(name: 'public_key')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode(name: 'base_uri')->isRequired()->end()
            ->scalarNode(name: 'base_uri_post')->defaultValue(value: null)->end()
            ->scalarNode(name: 'user_provider')->defaultValue(value: DefaultProvider::class)->end()
            ->scalarNode(name: 'use_session')->defaultValue(value: false)->end()
            ->scalarNode(name: 'verify')->defaultValue(value: true)->end()
            ->arrayNode(name: 'redirect')
                ->addDefaultsIfNotSet()
                ->children()
                    ->enumNode(name: 'type')->values(values: ['route', 'uri'])->defaultValue(value: 'route')->end()
                    ->scalarNode(name: 'route')->defaultValue(value: null)->end()
                    ->scalarNode(name: 'uri')->defaultValue(value: null)->end()
                    ->arrayNode(name: 'params')->prototype(type: 'variable')->end()->end()
                ->end()
            ->end()
            ->arrayNode(name: 'uris')->prototype(type: 'array')->prototype(type: 'variable')->end()->end()
        ->end();
        // @formatter:on

        $optionsNode->end();
    }

    public static function configureClient(ContainerBuilder $containerBuilder, string $clientServiceKey, string $base, string $key): void
    {
        $clientDefinition = $containerBuilder->register(id: $clientServiceKey, class: $containerBuilder->getParameter(name: $clientServiceKey . '.user_provider'));
        $options = $containerBuilder->getParameter(name: $clientServiceKey);
        unset($options['user_provider']);
        $clientDefinition->setArguments(arguments: [
            $key,
            new Reference(id: 'router'),
            new Reference(id: 'request_stack'),
            $options,
            [],
        ])
            ->addTag(name: $base);
    }

    public function getConfiguration(ArrayNodeDefinition $arrayNodeDefinition): void
    {
        // @formatter:off
        $arrayNodeDefinition
            ->children()
            ->arrayNode(name: Component::AUTH_OPENIDCONNECT)
                ->canBeEnabled()
                ->addDefaultsIfNotSet()
                ->children()
                    ->arrayNode(name: 'clients')->prototype(type: 'array')->prototype(type: 'variable')->end()->end()->end()
                ->end()
            ->end()
        ->end();
        // @formatter:on
    }

    public function loadComponent(ContainerBuilder $containerBuilder, ConfigurationInterface $configuration): void
    {
        $this->loadComponentConfiguration(base: AbstractAuthDependency::AUTH . '.' . Component::AUTH_OPENIDCONNECT, containerBuilder: $containerBuilder);
    }
}
