<?php declare(strict_types = 1);

namespace Vairogs\Translatable\Translation\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Vairogs\Core\DependencyInjection\Component;
use Vairogs\Core\DependencyInjection\Dependency;
use Vairogs\Core\Vairogs;
use function sprintf;

class TranslatableTranslationDependency implements Dependency
{
    /**
     * @noinspection NullPointerExceptionInspection
     */
    public function getConfiguration(ArrayNodeDefinition $arrayNodeDefinition): void
    {
        $arrayNodeDefinition
            ->children()
            ->arrayNode(name: Component::TRANSLATABLE_TRANSLATION)
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
    }

    public function loadComponent(ContainerBuilder $containerBuilder, ConfigurationInterface $configuration): void
    {
        // Part of interface, not needed in this component
    }
}
