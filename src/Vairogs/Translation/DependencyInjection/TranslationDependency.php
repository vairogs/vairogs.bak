<?php declare(strict_types = 1);

namespace Vairogs\Translation\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Vairogs\Utils\DependencyInjection\Component;
use Vairogs\Utils\DependencyInjection\Dependency;
use Vairogs\Utils\Vairogs;
use function sprintf;

class TranslationDependency implements Dependency
{
    /**
     * @noinspection NullPointerExceptionInspection
     */
    public function getConfiguration(ArrayNodeDefinition $arrayNodeDefinition): void
    {
        // @formatter:off
        $arrayNodeDefinition
            ->children()
            ->arrayNode(name: Component::TRANSLATION)
                ->canBeEnabled()
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode(name: 'table')->defaultValue(value: sprintf('%s_translations', Vairogs::VAIROGS))->end()
                    ->scalarNode(name: 'default_locale')->defaultValue(value: 'en')->end()
                    ->arrayNode(name: 'locales')
                        ->addDefaultChildrenIfNoneSet()
                        ->prototype(type: 'scalar')->defaultValue(value: 'en')->end()
                    ->end()
                ->end()
            ->end()
        ->end();
        // @formatter:on
    }

    public function loadComponent(ContainerBuilder $containerBuilder, ConfigurationInterface $configuration): void
    {
        // Part of interface, not needed in this component
    }
}
