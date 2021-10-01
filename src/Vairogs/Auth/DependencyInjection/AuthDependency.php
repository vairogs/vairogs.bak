<?php declare(strict_types = 1);

namespace Vairogs\Auth\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Vairogs\Auth\OpenID\DependencyInjection\AuthOpenIDDependency;
use Vairogs\Auth\OpenIDConnect\DependencyInjection\AuthOpenIDConnectDependency;
use Vairogs\Extra\Constants\Status;
use Vairogs\Utils\DependencyInjection\Component;
use Vairogs\Utils\DependencyInjection\DependecyLoaderTrait;
use Vairogs\Utils\DependencyInjection\Dependency;
use Vairogs\Utils\Vairogs;
use function sprintf;

class AuthDependency implements Dependency
{
    use DependecyLoaderTrait;

    public function getConfiguration(ArrayNodeDefinition $arrayNodeDefinition): void
    {
        // @formatter:off
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
        // @formatter:on
    }

    public function loadComponent(ContainerBuilder $containerBuilder, ConfigurationInterface $configuration): void
    {
        $enabledKey = sprintf('%s.%s.%s', Vairogs::VAIROGS, Component::AUTH, Status::ENABLED);

        if ($containerBuilder->hasParameter(name: $enabledKey) && true === $containerBuilder->getParameter(name: $enabledKey)) {
            $this->configureComponent(class: AuthOpenIDDependency::class, container: $containerBuilder, configuration: $configuration);
            $this->configureComponent(class: AuthOpenIDConnectDependency::class, container: $containerBuilder, configuration: $configuration);
        }
    }
}
