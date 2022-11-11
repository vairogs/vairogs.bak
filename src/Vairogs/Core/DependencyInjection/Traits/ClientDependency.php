<?php declare(strict_types = 1);

namespace Vairogs\Core\DependencyInjection\Traits;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Vairogs\Core\Functions;

use function sprintf;

trait ClientDependency
{
    public function loadComponentConfiguration(string $base, ContainerBuilder $container): void
    {
        $enabledKey = sprintf('%s.enabled', $base);

        if ($container->hasParameter(name: $enabledKey) && true === $container->getParameter(name: $enabledKey)) {
            $clients = $base . '.clients';

            foreach ($container->getParameter(name: $clients) as $key => $clientConfig) {
                $tree = new TreeBuilder(name: $key);
                $this->buildClientConfiguration(arrayNodeDefinition: $tree->getRootNode());
                $config = (new Processor())->process(configTree: $tree->buildTree(), configs: [$clientConfig]);

                foreach ((new Functions())->makeOneDimension(array: $config, base: $clientKey = $clients . '.' . $key) as $tkey => $value) {
                    $container->setParameter(name: $tkey, value: $value);
                }

                $this->configureClient(container: $container, clientServiceKey: $clientKey, base: $clients, key: $key);
            }
        }
    }

    abstract public function buildClientConfiguration(ArrayNodeDefinition $arrayNodeDefinition): void;

    abstract public function configureClient(ContainerBuilder $container, string $clientServiceKey, string $base, string $key): void;
}
