<?php declare(strict_types = 1);

namespace Vairogs\Component\Auth\OpenIDConnect\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpFoundation\Request;
use Vairogs\Component\Auth\OpenIDConnect\Configuration\DefaultProvider;
use Vairogs\Component\Utils\DependencyInjection\Component;
use Vairogs\Component\Utils\DependencyInjection\Dependency;

class AuthOpenIDConnectDependency implements Dependency
{
    /**
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     */
    public function getConfiguration(ArrayNodeDefinition $arrayNodeDefinition): void
    {
        // @formatter:off
        $arrayNodeDefinition
            ->children()
            ->arrayNode(Component::AUTH_OPENIDCONNECT)
                ->canBeEnabled()
                ->addDefaultsIfNotSet()
                ->children()
                    ->arrayNode('clients')->prototype('array')->prototype('variable')->end()->end()->end()
                ->end()
            ->end()
        ->end();
        // @formatter:on
    }

    public function loadComponent(ContainerBuilder $containerBuilder, ConfigurationInterface $configuration): void
    {
    }

    private function configureClient(ContainerBuilder $containerBuilder, string $clientServiceKey, string $base): void
    {
        $clientDefinition = $containerBuilder->register($clientServiceKey, $containerBuilder->getParameter($clientServiceKey . '.user_provider'));
        $clientDefinition->setArguments([
            $containerBuilder->getParameter($clientServiceKey),
            [],
            new Reference('router'),
            new Reference('request_stack'),
        ])
            ->addTag($base);
    }

    private function configureUri(ArrayNodeDefinition $arrayNodeDefinition): void
    {
        $arrayNodeDefinition->addDefaultsIfNotSet();
        $optionsNode = $arrayNodeDefinition->children();

        // @formatter:off
        $optionsNode
            ->arrayNode('params')->prototype('variable')->end()->end()
            ->arrayNode('url_params')->prototype('variable')->end()->end()
            ->enumNode('method')->values([Request::METHOD_GET, Request::METHOD_POST])->cannotBeEmpty()->end();
        // @formatter:on

        $optionsNode->end();
    }

    /**
     * @noinspection NullPointerExceptionInspection
     */
    private function buildClientConfiguration(ArrayNodeDefinition $arrayNodeDefinition): void
    {
        $arrayNodeDefinition->addDefaultsIfNotSet();
        $optionsNode = $arrayNodeDefinition->children();

        // @formatter:off
        $optionsNode
            ->scalarNode('client_key')->isRequired()->defaultValue(null)->end()
            ->scalarNode('client_secret')->defaultValue(null)->end()
            ->scalarNode('id_token_issuer')->isRequired()->defaultValue(null)->end()
            ->scalarNode('public_key')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('base_uri')->isRequired()->end()
            ->scalarNode('user_provider')->defaultValue(DefaultProvider::class)->end()
            ->scalarNode('use_session')->defaultValue(false)->end()
            ->scalarNode('verify')->defaultValue(true)->end()
            ->arrayNode('redirect')
                ->addDefaultsIfNotSet()
                ->children()
                    ->enumNode('type')->values(['route', 'uri'])->defaultValue('route')->end()
                    ->scalarNode('route')->defaultValue(null)->end()
                    ->scalarNode('uri')->defaultValue(null)->end()
                    ->arrayNode('params')->prototype('variable')->end()->end()
                ->end()
            ->end()
            ->arrayNode('uris')->prototype('array')->prototype('variable')->end()->end()
        ->end();
        // @formatter:on

        $optionsNode->end();
    }
}
