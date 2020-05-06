<?php declare(strict_types = 1);

namespace Vairogs\Component\Sitemap\DependencyInjection;

use Exception;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Vairogs\Component\Utils\Helper\Iter;
use Vairogs\Component\Utils\Vairogs;

class VairogsSitemapExtension extends Extension
{
    /**
     * @var string
     */
    public const ALIAS = 'sitemap';

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return Vairogs::ALIAS;
    }

    /**
     * @return string
     */
    public function getExtension(): string
    {
        return Vairogs::ALIAS . '.' . self::ALIAS;
    }

    /**
     * @param array $configs
     * @param ContainerBuilder $container
     *
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $parameters = $this->processConfiguration($configuration, $configs)[self::ALIAS];

        foreach (Iter::makeOneDimension([$this->getExtension() => $parameters]) as $key => $value) {
            $container->setParameter($key, $value);
        }
    }
}
