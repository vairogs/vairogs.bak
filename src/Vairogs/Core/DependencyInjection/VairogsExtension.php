<?php declare(strict_types = 1);

namespace Vairogs\Core\DependencyInjection;

use Spaghetti\DependencyInjection\AbstractExtension;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Vairogs\Auth\DependencyInjection\AuthDependency;
use Vairogs\Cache\DependencyInjection\CacheDependency;
use Vairogs\Sitemap\DependencyInjection\SitemapDependency;
use Vairogs\Twig\DependencyInjection\TwigDependency;

final class VairogsExtension extends AbstractExtension
{
    protected function configure(ContainerBuilder $container, ConfigurationInterface $configuration): void
    {
        $this->configureComponent(class: AuthDependency::class, container: $container, configuration: $configuration);
        $this->configureComponent(class: CacheDependency::class, container: $container, configuration: $configuration);
        $this->configureComponent(class: SitemapDependency::class, container: $container, configuration: $configuration);
        $this->configureComponent(class: TwigDependency::class, container: $container, configuration: $configuration);
    }

    protected function init(): ConfigurationInterface
    {
        return new Configuration();
    }
}
