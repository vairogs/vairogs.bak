<?php declare(strict_types = 1);

namespace Vairogs\Component\Cache\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Vairogs\Component\Utils\DependencyInjection\Component;
use Vairogs\Component\Utils\DependencyInjection\Dependency;

class CacheDependency implements Dependency
{
    /**
     * @inheritDoc
     * @noinspection NullPointerExceptionInspection
     */
    public function getConfiguration(ArrayNodeDefinition $node): void
    {
        // @formatter:off
        $node
            ->children()
            ->arrayNode(Component::CACHE)
                ->canBeEnabled()
            ->end()
        ->end();
        // @formatter:on
    }

    public function loadComponent(ContainerBuilder $container, ConfigurationInterface $configuration): void
    {
    }
}
