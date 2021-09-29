<?php declare(strict_types = 1);

namespace Vairogs\Utils\DependencyInjection;

use Exception;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Vairogs\Auth\DependencyInjection\AuthDependency;
use Vairogs\Cache\DependencyInjection\CacheDependency;
use Vairogs\Sitemap\DependencyInjection\SitemapDependency;
use Vairogs\Translation\DependencyInjection\TranslationDependency;
use Vairogs\Utils\Helper\Iteration;
use Vairogs\Utils\Helper\Php;
use Vairogs\Utils\Vairogs;
use function class_exists;

class VairogsExtension extends Extension
{
    /**
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $this->process(configs: $configs, container: $container, configuration: $configuration);
        $this->processComponents(container: $container, configuration: $configuration);
    }

    public function process(array $configs, ContainerBuilder $container, ConfigurationInterface $configuration): void
    {
        $parameters = $this->processConfiguration(configuration: $configuration, configs: $configs) ?? [];

        foreach (Iteration::makeOneDimension(array: [$this->getAlias() => $parameters]) as $key => $value) {
            $container->setParameter(name: $key, value: $value);
        }
    }

    public function getAlias(): string
    {
        return Vairogs::VAIROGS;
    }

    private function processComponents(ContainerBuilder $container, Configuration $configuration): void
    {
        $this->processCacheComponent(container: $container, configuration: $configuration);
        $this->processAuthComponent(container: $container, configuration: $configuration);
        $this->processSitemapComponent(container: $container, configuration: $configuration);
        $this->processTranslationComponent(container: $container, configuration: $configuration);
    }

    private function processCacheComponent(ContainerBuilder $container, ConfigurationInterface $configuration): void
    {
        if (class_exists(class: CacheDependency::class) && Php::classImplements(class: CacheDependency::class, interface: Dependency::class)) {
            (new CacheDependency())->loadComponent(containerBuilder: $container, configuration: $configuration);
        }
    }

    private function processAuthComponent(ContainerBuilder $container, ConfigurationInterface $configuration): void
    {
        if (class_exists(class: AuthDependency::class) && Php::classImplements(class: AuthDependency::class, interface: Dependency::class)) {
            (new AuthDependency())->loadComponent(containerBuilder: $container, configuration: $configuration);
        }
    }

    private function processSitemapComponent(ContainerBuilder $container, ConfigurationInterface $configuration): void
    {
        if (class_exists(class: SitemapDependency::class) && Php::classImplements(class: SitemapDependency::class, interface: Dependency::class)) {
            (new SitemapDependency())->loadComponent(containerBuilder: $container, configuration: $configuration);
        }
    }

    private function processTranslationComponent(ContainerBuilder $container, ConfigurationInterface $configuration): void
    {
        if (class_exists(class: TranslationDependency::class) && Php::classImplements(class: TranslationDependency::class, interface: Dependency::class)) {
            (new TranslationDependency())->loadComponent(containerBuilder: $container, configuration: $configuration);
        }
    }
}
