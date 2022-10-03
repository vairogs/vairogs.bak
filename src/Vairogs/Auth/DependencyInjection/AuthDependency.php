<?php declare(strict_types = 1);

namespace Vairogs\Auth\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Vairogs\Auth\OpenID\DependencyInjection\AuthOpenIDDependency;
use Vairogs\Auth\OpenIDConnect\DependencyInjection\AuthOpenIDConnectDependency;
use Vairogs\Core\DependencyInjection\Component;
use Vairogs\Core\DependencyInjection\DependecyLoader;
use Vairogs\Core\DependencyInjection\Dependency;
use Vairogs\Core\Vairogs;
use Vairogs\Extra\Constants\Status;

use function sprintf;

class AuthDependency implements Dependency
{
    use DependecyLoader;

    public function getConfiguration(ArrayNodeDefinition $arrayNodeDefinition): void
    {
        $authNode = $arrayNodeDefinition
            ->children()
                ->arrayNode(name: Component::AUTH)
                ->canBeEnabled();

        $this->appendComponent(class: AuthOpenIDDependency::class, arrayNodeDefinition: $authNode);
        $this->appendComponent(class: AuthOpenIDConnectDependency::class, arrayNodeDefinition: $authNode);

        $arrayNodeDefinition
            ->children()
            ->arrayNode(name: Component::AUTH)
            ->children();

        $arrayNodeDefinition
            ->append(node: $authNode)
            ->end();
    }

    public function loadComponent(ContainerBuilder $container, ConfigurationInterface $configuration): void
    {
        $enabledKey = sprintf('%s.%s.%s', Vairogs::VAIROGS, Component::AUTH, Status::ENABLED);

        if ($container->hasParameter(name: $enabledKey) && true === $container->getParameter(name: $enabledKey)) {
            $this->configureComponent(class: AuthOpenIDDependency::class, container: $container, configuration: $configuration);
            $this->configureComponent(class: AuthOpenIDConnectDependency::class, container: $container, configuration: $configuration);
        }
    }
}
