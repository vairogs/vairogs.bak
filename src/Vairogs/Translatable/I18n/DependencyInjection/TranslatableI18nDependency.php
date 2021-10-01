<?php declare(strict_types = 1);

namespace Vairogs\Translatable\I18n\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Vairogs\Utils\DependencyInjection\Component;
use Vairogs\Utils\DependencyInjection\Dependency;
use Vairogs\Utils\Vairogs;

class TranslatableI18nDependency implements Dependency
{
    public const ALIAS = Vairogs::VAIROGS . '.' . Component::TRANSLATABLE . '.' . Component::TRANSLATABLE_I18N;

    /**
     * @noinspection NullPointerExceptionInspection
     */
    public function getConfiguration(ArrayNodeDefinition $arrayNodeDefinition): void
    {
        // @formatter:off
        $arrayNodeDefinition
            ->children()
                ->arrayNode(name: Component::TRANSLATABLE_I18N)
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
