<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

interface Dependency
{
    /**
     * @param ArrayNodeDefinition $arrayNodeDefinition
     */
    public function getConfiguration(ArrayNodeDefinition $arrayNodeDefinition): void;

    /**
     * @param ContainerBuilder $containerBuilder
     * @param ConfigurationInterface $configuration
     */
    public function loadComponent(ContainerBuilder $containerBuilder, ConfigurationInterface $configuration): void;
}
