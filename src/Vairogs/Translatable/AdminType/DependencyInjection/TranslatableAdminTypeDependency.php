<?php declare(strict_types = 1);

namespace Vairogs\Translatable\AdminType\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Vairogs\Utils\DependencyInjection\Component;
use Vairogs\Utils\DependencyInjection\Dependency;

class TranslatableAdminTypeDependency implements Dependency
{
    public function getConfiguration(ArrayNodeDefinition $arrayNodeDefinition): void
    {
        // @formatter:off
        /* @noinspection NullPointerExceptionInspection */
        $arrayNodeDefinition
            ->children()
                ->arrayNode(name: Component::TRANSLATABLE_ADMINTYPE)
                    ->canBeEnabled()
                ->end()
            ->end();
        // @formatter:on
    }

    public function loadComponent(ContainerBuilder $containerBuilder, ConfigurationInterface $configuration): void
    {
        // Part of interface, not needed in this component
    }
}
