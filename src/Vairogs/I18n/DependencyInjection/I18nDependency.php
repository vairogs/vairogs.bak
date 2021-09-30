<?php declare(strict_types = 1);

namespace Vairogs\I18n\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Vairogs\Utils\DependencyInjection\Component;
use Vairogs\Utils\DependencyInjection\Dependency;

class I18nDependency implements Dependency
{
    /**
     * @noinspection NullPointerExceptionInspection
     */
    public function getConfiguration(ArrayNodeDefinition $arrayNodeDefinition): void
    {
        // @formatter:off
        $arrayNodeDefinition
            ->children()
                ->arrayNode(name: Component::I18N)
                    ->canBeEnabled()
                    ->addDefaultsIfNotSet()
                ->end()
            ->end();
        // @formatter:on
    }

    public function loadComponent(ContainerBuilder $containerBuilder, ConfigurationInterface $configuration): void
    {
        // Part of interface, not needed in this component
    }
}
