<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Vairogs\Component\Utils\Helper\Iter;
use Vairogs\Component\Utils\Vairogs;

abstract class VairogsExtension extends Extension
{
    /**
     * @return string
     */
    public function getAlias(): string
    {
        return Vairogs::ALIAS;
    }

    /**
     * @param array $configs
     * @param ContainerBuilder $container
     * @param ConfigurationInterface $configuration
     */
    public function process(array $configs, ContainerBuilder $container, ConfigurationInterface $configuration): void
    {
        $parameters = $this->processConfiguration($configuration, $configs)[$this->getExtensionAlias()] ?? [];

        foreach (Iter::makeOneDimension([$this->getExtension() => $parameters]) as $key => $value) {
            $container->setParameter($key, $value);
        }
    }

    /**
     * @return string
     */
    abstract public function getExtensionAlias(): string;

    /**
     * @return string
     */
    abstract public function getExtension(): string;
}
