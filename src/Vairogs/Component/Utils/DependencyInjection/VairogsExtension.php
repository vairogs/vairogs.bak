<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\DependencyInjection;

use Exception;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Vairogs\Component\Auth\DependencyInjection\AuthDependency;
use Vairogs\Component\Cache\DependencyInjection\CacheDependency;
use Vairogs\Component\Sitemap\DependencyInjection\SitemapDependency;
use Vairogs\Component\Utils\Helper\Iter;
use Vairogs\Component\Utils\Vairogs;
use function class_exists;

class VairogsExtension extends Extension
{
    /**
     * @param array $configs
     * @param ContainerBuilder $container
     *
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $this->process($configs, $container, $configuration);
        $this->processComponents($container, $configuration);
    }

    /**
     * @param array $configs
     * @param ContainerBuilder $container
     * @param ConfigurationInterface $configuration
     */
    public function process(array $configs, ContainerBuilder $container, ConfigurationInterface $configuration): void
    {
        $parameters = $this->processConfiguration($configuration, $configs) ?? [];

        foreach (Iter::makeOneDimension([$this->getAlias() => $parameters]) as $key => $value) {
            $container->setParameter($key, $value);
        }
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return Vairogs::VAIROGS;
    }

    /**
     * @param ContainerBuilder $container
     * @param ConfigurationInterface $configuration
     */
    private function processComponents(ContainerBuilder $container, ConfigurationInterface $configuration): void
    {
        $this->processCacheComponent($container, $configuration);
        $this->processAuthComponent($container, $configuration);
        $this->processSitemapComponent($container, $configuration);
    }

    /**
     * @param ContainerBuilder $container
     * @param ConfigurationInterface $configuration
     */
    private function processCacheComponent(ContainerBuilder $container, ConfigurationInterface $configuration): void
    {
        if (class_exists(CacheDependency::class)) {
            (new CacheDependency())->loadComponent($container, $configuration);
        }
    }

    /**
     * @param ContainerBuilder $container
     * @param ConfigurationInterface $configuration
     */
    private function processAuthComponent(ContainerBuilder $container, ConfigurationInterface $configuration): void
    {
        if (class_exists(AuthDependency::class)) {
            (new AuthDependency())->loadComponent($container, $configuration);
        }
    }

    /**
     * @param ContainerBuilder $container
     * @param ConfigurationInterface $configuration
     */
    private function processSitemapComponent(ContainerBuilder $container, ConfigurationInterface $configuration): void
    {
        if (class_exists(SitemapDependency::class)) {
            (new SitemapDependency())->loadComponent($container, $configuration);
        }
    }
}
