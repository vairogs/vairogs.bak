<?php declare(strict_types = 1);

namespace Vairogs\Twig\DependencyInjection;

use Spaghetti\DependencyInjection\Dependency;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TwigDependency implements Dependency
{
    public const TWIG = 'twig';

    public function loadComponent(ContainerBuilder $container, ConfigurationInterface $configuration): void
    {
        // Part of interface, not needed in this component
    }

    public function getConfiguration(ArrayNodeDefinition $arrayNodeDefinition): void
    {
        /* @noinspection NullPointerExceptionInspection */
        $arrayNodeDefinition
            ->children()
            ->arrayNode(name: self::TWIG)
                ->canBeEnabled()
                ->children()
                    ->arrayNode(name: 'classes')
                        ->scalarPrototype()->end()
                    ->end()
                ->end()
            ->end()
        ->end();
    }
}
