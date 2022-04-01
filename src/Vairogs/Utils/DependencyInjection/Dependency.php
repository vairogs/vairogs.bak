<?php declare(strict_types = 1);

namespace Vairogs\Utils\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

interface Dependency
{
    final public const ENABLED = 'enabled';

    public function getConfiguration(ArrayNodeDefinition $arrayNodeDefinition): void;

    public function loadComponent(ContainerBuilder $containerBuilder, ConfigurationInterface $configuration): void;
}
