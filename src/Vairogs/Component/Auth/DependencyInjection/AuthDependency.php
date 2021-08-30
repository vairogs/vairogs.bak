<?php declare(strict_types = 1);

namespace Vairogs\Component\Auth\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Vairogs\Component\Auth\OpenID\DependencyInjection\AuthOpenIDDependency;
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
                ->arrayNode(Component::AUTH)
                ->canBeEnabled();
        $this->appendOpenIDConfiguration($authNode);

        $arrayNodeDefinition
            ->children()
            ->arrayNode(Component::AUTH)
            ->children();

        $arrayNodeDefinition
            ->append($authNode)
            ->end();
        // @formatter:on
    }

    private function appendOpenIDConfiguration(ArrayNodeDefinition $arrayNodeDefinition): void
    {
        if (class_exists(AuthOpenIDDependency::class) && Php::classImplements(AuthOpenIDDependency::class, Dependency::class)) {
            (new AuthOpenIDDependency())->getConfiguration($arrayNodeDefinition);
        }
    }

    public function loadComponent(ContainerBuilder $containerBuilder, ConfigurationInterface $configuration): void
    {
        $enabledKey = sprintf('%s.%s.%s', Vairogs::VAIROGS, Component::AUTH, Dependency::ENABLED);
        if ($containerBuilder->hasParameter($enabledKey) && true === $containerBuilder->getParameter($enabledKey) && class_exists(AuthOpenIDDependency::class)) {
            (new AuthOpenIDDependency())->loadComponent($containerBuilder, $configuration);
        }
    }
}
