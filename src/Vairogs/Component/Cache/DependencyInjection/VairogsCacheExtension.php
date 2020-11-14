<?php declare(strict_types = 1);

namespace Vairogs\Component\Cache\DependencyInjection;

use Exception;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Vairogs\Component\Cache\Cache;
use Vairogs\Component\Utils\DependencyInjection\VairogsExtension;
use Vairogs\Component\Utils\Vairogs;

class VairogsCacheExtension extends VairogsExtension
{
    /**
     * @return string
     */
    public function getExtension(): string
    {
        return Vairogs::ALIAS . '.' . $this->getExtensionAlias();
    }

    /**
     * @return string
     */
    public function getExtensionAlias(): string
    {
        return Cache::ALIAS;
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
        $this->process($configs, $container, $configuration);
    }
}
