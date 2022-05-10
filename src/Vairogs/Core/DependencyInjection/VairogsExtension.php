<?php declare(strict_types = 1);

namespace Vairogs\Core\DependencyInjection;

use Exception;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Vairogs\Auth\DependencyInjection\AuthDependency;
use Vairogs\Cache\DependencyInjection\CacheDependency;
use Vairogs\Sitemap\DependencyInjection\SitemapDependency;
use Vairogs\Utils\Helper\Util;

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
    }

    public function process(array $configs, ContainerBuilder $container, ConfigurationInterface $configuration): void
    {
        foreach ((new Util())->makeOneDimension(array: [$this->getAlias() => $this->processConfiguration(configuration: $configuration, configs: $configs)]) as $key => $value) {
            $container->setParameter(name: $key, value: $value);
        }
    }
}
