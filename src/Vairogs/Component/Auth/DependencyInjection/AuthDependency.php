<?php declare(strict_types = 1);

namespace Vairogs\Component\Auth\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Vairogs\Component\Auth\OpenID\DependencyInjection\AuthOpenIDDependency;
use Vairogs\Component\Utils\DependencyInjection\Component;
use Vairogs\Component\Utils\DependencyInjection\Dependency;
use Vairogs\Component\Utils\Vairogs;
use function class_exists;

class AuthDependency implements Dependency
{
    /**
     * @inheritDoc
     */
    public function getConfiguration(ArrayNodeDefinition $node): void
    {
        // @formatter:off
        $authNode = $node
            ->children()
                ->arrayNode(Component::AUTH);
        $this->appendOpenIDConfiguration($authNode);

        $node
            ->children()
            ->arrayNode(Component::AUTH)
            ->canBeEnabled()
            ->children()
            ->append($authNode)
            ->end();
        // @formatter:on
    }

    /**
     * @param ArrayNodeDefinition $node
     */
    private function appendOpenIDConfiguration(ArrayNodeDefinition $node): void
    {
        if (class_exists(AuthOpenIDDependency::class)) {
            (new AuthOpenIDDependency())->getConfiguration($node);
        }
    }

    /**
     * @inheritDoc
     */
    public function loadComponent(ContainerBuilder $container, ConfigurationInterface $configuration): void
    {
        $enbledKey = Vairogs::VAIROGS . '.' . Component::AUTH . '.enabled';
        if ($container->hasParameter($enbledKey) && true === $container->getParameter($enbledKey)) {
            if (class_exists(AuthOpenIDDependency::class)) {
                (new AuthOpenIDDependency())->loadComponent($container, $configuration);
            }
        }
    }
}
