<?php declare(strict_types = 1);

namespace Vairogs\Component\Auth\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Vairogs\Component\Utils\DependencyInjection\Dependency;
use Vairogs\Component\Utils\Helper\Iteration;
use function sprintf;

abstract class AbstractAuthComponentDependency implements Dependency
{
    public function loadComponentConfiguration(string $base, ContainerBuilder $containerBuilder): void
    {
        $enabledKey = sprintf('%s.%s', $base, Dependency::ENABLED);

        if ($containerBuilder->hasParameter(name: $enabledKey) && true === $containerBuilder->getParameter(name: $enabledKey)) {
            $clientsKey = $base . '.clients';

            foreach ($containerBuilder->getParameter(name: $clientsKey) as $key => $clientConfig) {
                $tree = new TreeBuilder(name: $key);
                $node = $tree->getRootNode();
                static::buildClientConfiguration(arrayNodeDefinition: $node);
                $config = (new Processor())->process(configTree: $tree->buildTree(), configs: [$clientConfig]);
                $clientServiceKey = $clientsKey . '.' . $key;

                foreach (Iteration::makeOneDimension(array: $config, base: $clientServiceKey) as $tkey => $value) {
                    $containerBuilder->setParameter(name: $tkey, value: $value);
                }

                static::configureClient(containerBuilder: $containerBuilder, clientServiceKey: $clientServiceKey, base: $clientsKey, key: $key);
            }
        }
    }

    abstract public static function buildClientConfiguration(ArrayNodeDefinition $arrayNodeDefinition): void;

    abstract public static function configureClient(ContainerBuilder $containerBuilder, string $clientServiceKey, string $base, string $key): void;
}
