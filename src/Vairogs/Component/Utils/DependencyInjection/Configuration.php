<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Vairogs\Component\Auth\DependencyInjection\AuthDependency;
use Vairogs\Component\Cache\DependencyInjection\CacheDependency;
use Vairogs\Component\Sitemap\DependencyInjection\SitemapDependency;
use Vairogs\Component\Translation\DependencyInjection\TranslationDependency;
use Vairogs\Component\Utils\Helper\Php;
use Vairogs\Component\Utils\Vairogs;
use function class_exists;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder(name: Vairogs::VAIROGS);
        $rootNode = $treeBuilder->getRootNode();

        $this->appendCacheNode(arrayNodeDefinition: $rootNode);
        $this->appendAuthNode(arrayNodeDefinition: $rootNode);
        $this->appendSitemapNode(arrayNodeDefinition: $rootNode);
        $this->appendTranslationNode(arrayNodeDefinition: $rootNode);

        return $treeBuilder;
    }

    private function appendCacheNode(ArrayNodeDefinition $arrayNodeDefinition): void
    {
        if (class_exists(class: CacheDependency::class) && Php::classImplements(class: CacheDependency::class, interface: Dependency::class)) {
            (new CacheDependency())->getConfiguration(arrayNodeDefinition: $arrayNodeDefinition);
        }
    }

    private function appendAuthNode(ArrayNodeDefinition $arrayNodeDefinition): void
    {
        if (class_exists(class: AuthDependency::class) && Php::classImplements(class: AuthDependency::class, interface: Dependency::class)) {
            (new AuthDependency())->getConfiguration(arrayNodeDefinition: $arrayNodeDefinition);
        }
    }

    private function appendSitemapNode(ArrayNodeDefinition $arrayNodeDefinition): void
    {
        if (class_exists(class: SitemapDependency::class) && Php::classImplements(class: SitemapDependency::class, interface: Dependency::class)) {
            (new SitemapDependency())->getConfiguration(arrayNodeDefinition: $arrayNodeDefinition);
        }
    }

    private function appendTranslationNode(ArrayNodeDefinition $arrayNodeDefinition): void
    {
        if (class_exists(class: TranslationDependency::class) && Php::classImplements(class: TranslationDependency::class, interface: Dependency::class)) {
            (new TranslationDependency())->getConfiguration(arrayNodeDefinition: $arrayNodeDefinition);
        }
    }
}
