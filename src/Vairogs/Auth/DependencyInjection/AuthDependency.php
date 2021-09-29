<?php declare(strict_types = 1);

namespace Vairogs\Auth\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Vairogs\Auth\OpenID\DependencyInjection\AuthOpenIDDependency;
use Vairogs\Auth\OpenIDConnect\DependencyInjection\AuthOpenIDConnectDependency;
use Vairogs\Component\Utils\DependencyInjection\Component;
use Vairogs\Component\Utils\DependencyInjection\Dependency;
use Vairogs\Component\Utils\Helper\Php;
use Vairogs\Component\Utils\Vairogs;
use function class_exists;
use function sprintf;

class AuthDependency implements Dependency
{
    public function getConfiguration(ArrayNodeDefinition $arrayNodeDefinition): void
    {
        // @formatter:off
        $authNode = $arrayNodeDefinition
            ->children()
                ->arrayNode(name: Component::AUTH)
                ->canBeEnabled();
        $this->appendOpenIDConfiguration(arrayNodeDefinition: $authNode);
        $this->appendOpenIDConnectConfiguration(arrayNodeDefinition: $authNode);

        $arrayNodeDefinition
            ->children()
            ->arrayNode(name: Component::AUTH)
            ->children();

        $arrayNodeDefinition
            ->append(node: $authNode)
            ->end();
        // @formatter:on
    }

    public function loadComponent(ContainerBuilder $containerBuilder, ConfigurationInterface $configuration): void
    {
        $enabledKey = sprintf('%s.%s.%s', Vairogs::VAIROGS, Component::AUTH, Dependency::ENABLED);

        if ($containerBuilder->hasParameter(name: $enabledKey) && true === $containerBuilder->getParameter(name: $enabledKey)) {
            $this->loadOpenIDComponent(containerBuilder: $containerBuilder, configuration: $configuration);
            $this->loadOpenIDConnectComponent(containerBuilder: $containerBuilder, configuration: $configuration);
        }
    }

    private function appendOpenIDConfiguration(ArrayNodeDefinition $arrayNodeDefinition): void
    {
        if (class_exists(class: AuthOpenIDDependency::class) && Php::classImplements(class: AuthOpenIDDependency::class, interface: Dependency::class)) {
            (new AuthOpenIDDependency())->getConfiguration(arrayNodeDefinition: $arrayNodeDefinition);
        }
    }

    private function appendOpenIDConnectConfiguration(ArrayNodeDefinition $arrayNodeDefinition): void
    {
        if (class_exists(class: AuthOpenIDConnectDependency::class) && Php::classImplements(class: AuthOpenIDConnectDependency::class, interface: Dependency::class)) {
            (new AuthOpenIDConnectDependency())->getConfiguration(arrayNodeDefinition: $arrayNodeDefinition);
        }
    }

    private function loadOpenIDComponent(ContainerBuilder $containerBuilder, ConfigurationInterface $configuration): void
    {
        if (class_exists(class: AuthOpenIDDependency::class) && Php::classImplements(class: AuthOpenIDDependency::class, interface: Dependency::class)) {
            (new AuthOpenIDDependency())->loadComponent(containerBuilder: $containerBuilder, configuration: $configuration);
        }
    }

    private function loadOpenIDConnectComponent(ContainerBuilder $containerBuilder, ConfigurationInterface $configuration): void
    {
        if (class_exists(class: AuthOpenIDConnectDependency::class) && Php::classImplements(class: AuthOpenIDConnectDependency::class, interface: Dependency::class)) {
            (new AuthOpenIDConnectDependency())->loadComponent(containerBuilder: $containerBuilder, configuration: $configuration);
        }
    }
}
