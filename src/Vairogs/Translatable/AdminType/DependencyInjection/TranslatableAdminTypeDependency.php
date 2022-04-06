<?php declare(strict_types = 1);

namespace Vairogs\Translatable\AdminType\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Vairogs\Core\DependencyInjection\Component;
use Vairogs\Core\DependencyInjection\Dependency;

class TranslatableAdminTypeDependency implements Dependency
{
    public function getConfiguration(ArrayNodeDefinition $arrayNodeDefinition): void
    {
        /* @noinspection NullPointerExceptionInspection */
        $arrayNodeDefinition
            ->children()
                ->arrayNode(name: Component::TRANSLATABLE_ADMINTYPE)
                    ->canBeEnabled()
                ->end()
            ->end();
    }

    public function loadComponent(ContainerBuilder $containerBuilder, ConfigurationInterface $configuration): void
    {
        // Part of interface, not needed in this component
    }
}
