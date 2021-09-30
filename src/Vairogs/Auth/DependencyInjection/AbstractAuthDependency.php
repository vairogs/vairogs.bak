<?php declare(strict_types = 1);

namespace Vairogs\Auth\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Vairogs\Extra\Constants\Status;
use Vairogs\Utils\DependencyInjection\Component;
use Vairogs\Utils\DependencyInjection\Dependency;
use Vairogs\Utils\Helper\Iteration;
use Vairogs\Utils\Vairogs;
use function sprintf;

abstract class AbstractAuthDependency implements Dependency
{
    public const AUTH = Vairogs::VAIROGS . '.' . Component::AUTH;

    public function loadComponentConfiguration(string $base, ContainerBuilder $containerBuilder): void
    {
        $enabledKey = sprintf('%s.%s', $base, Status::ENABLED);

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