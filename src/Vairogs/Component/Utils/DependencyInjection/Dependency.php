<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

interface Dependency
{
    /**
     * @param ArrayNodeDefinition $node
     */
    public function getConfiguration(ArrayNodeDefinition $node): void;

    /**
     * @param ContainerBuilder $container
     * @param ConfigurationInterface $configuration
     */
    public function loadComponent(ContainerBuilder $container, ConfigurationInterface $configuration): void;
}
