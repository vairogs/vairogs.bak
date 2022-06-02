<?php declare(strict_types = 1);

namespace Vairogs\Auth\OpenIDConnect\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\PropertyInfo\Type;
use Vairogs\Auth\DependencyInjection\AbstractAuthChildDependency;
use Vairogs\Auth\OpenIDConnect\Configuration\DefaultProvider;
use Vairogs\Auth\OpenIDConnect\Utils\Constants\Enum\Redirect;
use Vairogs\Core\DependencyInjection\Component;
use Vairogs\Extra\Constants\Definition;
use Vairogs\Extra\Constants\Service;

class AuthOpenIDConnectDependency extends AbstractAuthChildDependency
{
    public function buildClientConfiguration(ArrayNodeDefinition $arrayNodeDefinition): void
    {
        $arrayNodeDefinition->addDefaultsIfNotSet();
        $optionsNode = $arrayNodeDefinition->children();

        /* @noinspection NullPointerExceptionInspection */
        /* @noinspection PhpPossiblePolymorphicInvocationInspection */
        $optionsNode
            ->scalarNode(name: 'base_uri')->isRequired()->end()
            ->scalarNode(name: 'base_uri_post')->defaultValue(value: null)->end()
            ->scalarNode(name: 'client_id')->isRequired()->defaultValue(value: null)->end()
            ->scalarNode(name: 'client_secret')->defaultValue(value: null)->end()
            ->scalarNode(name: 'id_token_issuer')->isRequired()->defaultValue(value: null)->end()
            ->scalarNode(name: 'public_key')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode(name: 'use_session')->defaultValue(value: false)->end()
            ->scalarNode(name: 'user_provider')->defaultValue(value: DefaultProvider::class)->end()
            ->scalarNode(name: 'verify')->defaultValue(value: true)->end()
            ->arrayNode(name: 'redirect')
                ->addDefaultsIfNotSet()
                ->children()
                    ->enumNode(name: 'type')->values(values: Redirect::getCases())->defaultValue(value: Redirect::ROUTE->value)->end()
                    ->scalarNode(name: Redirect::ROUTE->value)->defaultValue(value: null)->end()
                    ->scalarNode(name: Redirect::URI->value)->defaultValue(value: null)->end()
                    ->arrayNode(name: 'params')->prototype(type: Definition::VARIABLE)->end()->end()
                ->end()
            ->end()
            ->arrayNode(name: 'uris')->prototype(type: Type::BUILTIN_TYPE_ARRAY)->prototype(type: Definition::VARIABLE)->end()->end()
        ->end();

        /* @noinspection PhpUnreachableStatementInspection */
        $optionsNode->end();
    }

    public function configureClient(ContainerBuilder $container, string $clientServiceKey, string $base, string $key): void
    {
        $clientDefinition = $container->register(id: $clientServiceKey, class: $container->getParameter(name: $clientServiceKey . '.user_provider'));
        $options = $container->getParameter(name: $clientServiceKey);
        unset($options['user_provider']);
        $clientDefinition->setArguments(arguments: [
            $key,
            new Reference(id: Service::ROUTER),
            new Reference(id: Service::REQUEST_STACK),
            $options,
            [],
        ])
        ->addTag(name: $base);
    }

    public function getConfiguration(ArrayNodeDefinition $arrayNodeDefinition): void
    {
        /* @noinspection PhpPossiblePolymorphicInvocationInspection */
        $arrayNodeDefinition
            ->children()
            ->arrayNode(name: Component::AUTH_OPENIDCONNECT)
                ->canBeEnabled()
                ->addDefaultsIfNotSet()
                ->children()
                    ->arrayNode(name: 'clients')->prototype(type: Type::BUILTIN_TYPE_ARRAY)->prototype(type: Definition::VARIABLE)->end()->end()->end()
                ->end()
            ->end()
        ->end();
    }

    public function loadComponent(ContainerBuilder $container, ConfigurationInterface $configuration): void
    {
        $this->loadComponentConfiguration(base: AbstractAuthChildDependency::AUTH . '.' . Component::AUTH_OPENIDCONNECT, container: $container);
    }
}
