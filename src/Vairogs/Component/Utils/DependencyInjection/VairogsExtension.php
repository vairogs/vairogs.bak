<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\DependencyInjection;

use Exception;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Vairogs\Component\Auth\DependencyInjection\AuthDependency;
use Vairogs\Component\Cache\DependencyInjection\CacheDependency;
use Vairogs\Component\Sitemap\DependencyInjection\SitemapDependency;
use Vairogs\Component\Translation\DependencyInjection\TranslationDependency;
use Vairogs\Component\Utils\Helper\Iter;
use Vairogs\Component\Utils\Vairogs;
use function class_exists;

class VairogsExtension extends Extension
{
    /**
     * @param array $configs
     * @param ContainerBuilder $containerBuilder
     *
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $containerBuilder): void
    {
        $configuration = new Configuration();
        $this->process($configs, $containerBuilder, $configuration);
        $this->processComponents($containerBuilder, $configuration);
    }

    /**
     * @param array $configs
     * @param ContainerBuilder $containerBuilder
     * @param ConfigurationInterface $configuration
     */
    public function process(array $configs, ContainerBuilder $containerBuilder, ConfigurationInterface $configuration): void
    {
        $parameters = $this->processConfiguration($configuration, $configs) ?? [];

        foreach (Iter::makeOneDimension([$this->getAlias() => $parameters]) as $key => $value) {
            $containerBuilder->setParameter($key, $value);
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
     * @param ContainerBuilder $containerBuilder
     * @param ConfigurationInterface $configuration
     */
    private function processComponents(ContainerBuilder $containerBuilder, ConfigurationInterface $configuration): void
    {
        $this->processCacheComponent($containerBuilder, $configuration);
        $this->processAuthComponent($containerBuilder, $configuration);
        $this->processSitemapComponent($containerBuilder, $configuration);
        $this->processTranslationComponent($containerBuilder, $configuration);
    }

    /**
     * @param ContainerBuilder $containerBuilder
     * @param ConfigurationInterface $configuration
     */
    private function processCacheComponent(ContainerBuilder $containerBuilder, ConfigurationInterface $configuration): void
    {
        if (class_exists(CacheDependency::class)) {
            (new CacheDependency())->loadComponent($containerBuilder, $configuration);
        }
    }

    /**
     * @param ContainerBuilder $containerBuilder
     * @param ConfigurationInterface $configuration
     */
    private function processAuthComponent(ContainerBuilder $containerBuilder, ConfigurationInterface $configuration): void
    {
        if (class_exists(AuthDependency::class)) {
            (new AuthDependency())->loadComponent($containerBuilder, $configuration);
        }
    }

    /**
     * @param ContainerBuilder $containerBuilder
     * @param ConfigurationInterface $configuration
     */
    private function processSitemapComponent(ContainerBuilder $containerBuilder, ConfigurationInterface $configuration): void
    {
        if (class_exists(SitemapDependency::class)) {
            (new SitemapDependency())->loadComponent($containerBuilder, $configuration);
        }
    }

    /**
     * @param ContainerBuilder $containerBuilder
     * @param ConfigurationInterface $configuration
     */
    private function processTranslationComponent(ContainerBuilder $containerBuilder, ConfigurationInterface $configuration): void
    {
        if (class_exists(TranslationDependency::class)) {
            (new TranslationDependency())->loadComponent($containerBuilder, $configuration);
        }
    }
}
