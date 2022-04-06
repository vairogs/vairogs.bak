<?php declare(strict_types = 1);

namespace Vairogs\Core\DependencyInjection;

use Exception;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Vairogs\Auth\DependencyInjection\AuthDependency;
use Vairogs\Cache\DependencyInjection\CacheDependency;
use Vairogs\Core\Vairogs;
use Vairogs\Sitemap\DependencyInjection\SitemapDependency;
use Vairogs\Translatable\DependencyInjection\TranslatableDependency;
use Vairogs\Utils\Helper\Iteration;

class VairogsExtension extends Extension
{
    use DependecyLoaderTrait;

    /**
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $this->process(configs: $configs, container: $container, configuration: $configuration);

        $this->configureComponent(class: CacheDependency::class, container: $container, configuration: $configuration);
        $this->configureComponent(class: AuthDependency::class, container: $container, configuration: $configuration);
        $this->configureComponent(class: SitemapDependency::class, container: $container, configuration: $configuration);
        $this->configureComponent(class: TranslatableDependency::class, container: $container, configuration: $configuration);
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
}
