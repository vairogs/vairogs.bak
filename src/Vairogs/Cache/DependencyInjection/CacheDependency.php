<?php declare(strict_types = 1);

namespace Vairogs\Cache\DependencyInjection;

use SimpleToImplement\DependencyInjection\Dependency;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CacheDependency implements Dependency
{
    public const CACHE = 'cache';

    /**
     * @noinspection NullPointerExceptionInspection
     */
    public function getConfiguration(ArrayNodeDefinition $arrayNodeDefinition): void
    {
        $arrayNodeDefinition
            ->children()
            ->arrayNode(name: self::CACHE)
                ->canBeEnabled()
            ->end()
        ->end();
    }

    public function loadComponent(ContainerBuilder $container, ConfigurationInterface $configuration): void
    {
        // Part of interface, not needed in this component
    }
}
