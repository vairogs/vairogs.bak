<?php declare(strict_types = 1);

namespace Vairogs\Core\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Vairogs\Auth\DependencyInjection\AuthDependency;
use Vairogs\Cache\DependencyInjection\CacheDependency;
use Vairogs\Sitemap\DependencyInjection\SitemapDependency;

final class VairogsExtension extends AbstractExtension
{
    protected function configure(ContainerBuilder $container, Configuration $configuration): void
    {
        $this->configureComponent(class: AuthDependency::class, container: $container, configuration: $configuration);
        $this->configureComponent(class: CacheDependency::class, container: $container, configuration: $configuration);
        $this->configureComponent(class: SitemapDependency::class, container: $container, configuration: $configuration);
    }
}
