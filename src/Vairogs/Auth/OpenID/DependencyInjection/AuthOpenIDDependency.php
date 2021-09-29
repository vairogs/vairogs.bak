<?php declare(strict_types = 1);

namespace Vairogs\Auth\OpenID\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Vairogs\Auth\DependencyInjection\AbstractAuthDependency;
use Vairogs\Auth\OpenID\OpenIDProvider;
use Vairogs\Component\Utils\DependencyInjection\Component;

class AuthOpenIDDependency extends AbstractAuthDependency
{
    public static function buildClientConfiguration(ArrayNodeDefinition $arrayNodeDefinition): void
    {
        $arrayNodeDefinition->addDefaultsIfNotSet();
        $optionsNode = $arrayNodeDefinition->children();

        // @formatter:off
        $optionsNode
            ->scalarNode(name: 'api_key')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode(name: 'openid_url')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode(name: 'preg_check')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode(name: 'ns_mode')->defaultValue(value: 'sreg')->end()
            ->scalarNode(name: 'user_builder')->isRequired()->end()
            ->scalarNode(name: 'user_class')->defaultValue(value: null)->end()
            ->scalarNode(name: 'redirect_route')->isRequired()->cannotBeEmpty()->end()
            ->arrayNode(name: 'provider_options')->prototype(type: 'variable')->end()->end();
        // @formatter:on

        $optionsNode->end();
    }

    public static function configureClient(ContainerBuilder $containerBuilder, string $clientServiceKey, string $base, string $key): void
    {
        $clientDefinition = $containerBuilder->register(id: $clientServiceKey, class: OpenIDProvider::class);
        $clientDefinition->setArguments(arguments: [
            new Reference(id: 'request_stack'),
            new Reference(id: 'router'),
            $key,
            $containerBuilder->getParameter(name: 'kernel.cache_dir'),
            $containerBuilder->getParameter(name: $clientServiceKey),
        ])
            ->addTag(name: $base);
    }

    /**
     * @noinspection NullPointerExceptionInspection
     */
    public function getConfiguration(ArrayNodeDefinition $arrayNodeDefinition): void
    {
        // @formatter:off
        $arrayNodeDefinition
            ->children()
            ->arrayNode(name: Component::AUTH_OPENID)
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
        $this->loadComponentConfiguration(base: AbstractAuthDependency::AUTH . '.' . Component::AUTH_OPENID, containerBuilder: $containerBuilder);
    }
}
