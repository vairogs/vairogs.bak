<?php declare(strict_types = 1);

namespace Vairogs\Core\DependencyInjection;

use Exception;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Vairogs\Core\Functions;

abstract class AbstractExtension extends Extension
{
    use Traits\DependecyLoader;

    /**
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = $this->init();
        $this->process(configs: $configs, container: $container, configuration: $configuration);
        $this->configure(container: $container, configuration: $configuration);
    }

    /**
     * @param array<int, array> $configs
     */
    public function process(array $configs, ContainerBuilder $container, ConfigurationInterface $configuration): void
    {
        foreach ((new Functions())->makeOneDimension(array: [$this->getAlias() => $this->processConfiguration(configuration: $configuration, configs: $configs)]) as $key => $value) {
            $container->setParameter(name: $key, value: $value);
        }
    }

    abstract protected function init(): ConfigurationInterface;

    abstract protected function configure(ContainerBuilder $container, ConfigurationInterface $configuration): void;
}
