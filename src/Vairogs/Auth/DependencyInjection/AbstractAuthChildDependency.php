<?php declare(strict_types = 1);

namespace Vairogs\Auth\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Vairogs\Core\DependencyInjection\Component;
use Vairogs\Core\DependencyInjection\Dependency;
use Vairogs\Core\Vairogs;
use Vairogs\Extra\Constants\Status;
use Vairogs\Utils\Helper\Util;
use function sprintf;

abstract class AbstractAuthChildDependency implements Dependency
{
    final public const AUTH = Vairogs::VAIROGS . '.' . Component::AUTH;

    public function loadComponentConfiguration(string $base, ContainerBuilder $container): void
    {
        $enabledKey = sprintf('%s.%s', $base, Status::ENABLED);

        if ($container->hasParameter(name: $enabledKey) && true === $container->getParameter(name: $enabledKey)) {
            $clientsKey = $base . '.clients';

            foreach ($container->getParameter(name: $clientsKey) as $key => $clientConfig) {
                $tree = new TreeBuilder(name: $key);
                $node = $tree->getRootNode();
                $this->buildClientConfiguration(arrayNodeDefinition: $node);
                $config = (new Processor())->process(configTree: $tree->buildTree(), configs: [$clientConfig]);
                $clientServiceKey = $clientsKey . '.' . $key;

                foreach ((new Util())->makeOneDimension(array: $config, base: $clientServiceKey) as $tkey => $value) {
                    $container->setParameter(name: $tkey, value: $value);
                }

                $this->configureClient(container: $container, clientServiceKey: $clientServiceKey, base: $clientsKey, key: $key);
            }
        }
    }

    abstract public function buildClientConfiguration(ArrayNodeDefinition $arrayNodeDefinition): void;

    abstract public function configureClient(ContainerBuilder $container, string $clientServiceKey, string $base, string $key): void;
}
