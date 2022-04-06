<?php declare(strict_types = 1);

namespace Vairogs\Translatable\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Vairogs\Core\DependencyInjection\Component;
use Vairogs\Core\DependencyInjection\DependecyLoaderTrait;
use Vairogs\Core\DependencyInjection\Dependency;
use Vairogs\Core\Vairogs;
use Vairogs\Extra\Constants\Status;
use Vairogs\Translatable\AdminType\DependencyInjection\TranslatableAdminTypeDependency;
use Vairogs\Translatable\Translation\DependencyInjection\TranslatableTranslationDependency;
use function sprintf;

class TranslatableDependency implements Dependency
{
    use DependecyLoaderTrait;

    public function getConfiguration(ArrayNodeDefinition $arrayNodeDefinition): void
    {
        $translatableNode = $arrayNodeDefinition
            ->children()
            ->arrayNode(name: Component::TRANSLATABLE)
            ->canBeEnabled();

        $this->appendComponent(class: TranslatableAdminTypeDependency::class, arrayNodeDefinition: $translatableNode);
        $this->appendComponent(class: TranslatableTranslationDependency::class, arrayNodeDefinition: $translatableNode);

        $arrayNodeDefinition
            ->children()
            ->arrayNode(name: Component::TRANSLATABLE)
            ->children();

        $arrayNodeDefinition
            ->append(node: $translatableNode)
            ->end();
    }

    public function loadComponent(ContainerBuilder $containerBuilder, ConfigurationInterface $configuration): void
    {
        $enabledKey = sprintf('%s.%s.%s', Vairogs::VAIROGS, Component::TRANSLATABLE, Status::ENABLED);

        if ($containerBuilder->hasParameter(name: $enabledKey) && true === $containerBuilder->getParameter(name: $enabledKey)) {
            $this->configureComponent(class: TranslatableAdminTypeDependency::class, container: $containerBuilder, configuration: $configuration);
            $this->configureComponent(class: TranslatableTranslationDependency::class, container: $containerBuilder, configuration: $configuration);
        }
    }
}
