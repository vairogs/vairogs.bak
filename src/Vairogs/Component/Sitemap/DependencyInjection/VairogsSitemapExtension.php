<?php declare(strict_types = 1);

namespace Vairogs\Sitemap\DependencyInjection;

use Exception;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Vairogs\Utils\Iter;

class VairogsSitemapExtension extends Extension
{
    /**
     * @var string
     */
    public const EXTENSION = 'vairogs.sitemap';

    /**
     * @var string
     */
    public const ALIAS = 'vairogs_sitemap';

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return self::ALIAS;
    }

    /**
     * @param array $configs
     * @param ContainerBuilder $container
     *
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration($this->getAlias());
        $parameters = $this->processConfiguration($configuration, $configs);

        foreach (Iter::makeOneDimension([self::EXTENSION => $parameters]) as $key => $value) {
            $container->setParameter($key, $value);
        }
    }
}
