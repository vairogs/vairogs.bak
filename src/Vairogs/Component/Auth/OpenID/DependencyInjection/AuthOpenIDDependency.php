<?php declare(strict_types = 1);

namespace Vairogs\Component\Auth\OpenID\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Vairogs\Component\Auth\OpenID\OpenIDProvider;
use Vairogs\Component\Utils\DependencyInjection\Component;
use Vairogs\Component\Utils\DependencyInjection\Dependency;
use Vairogs\Component\Utils\Helper\Iter;
use Vairogs\Component\Utils\Vairogs;

class AuthOpenIDDependency implements Dependency
{
    /**
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     */
    public function getConfiguration(ArrayNodeDefinition $arrayNodeDefinition): void
    {
        // @formatter:off
        $arrayNodeDefinition
            ->children()
            ->arrayNode(Component::AUTH_OPENID)
                ->canBeEnabled()
                ->addDefaultsIfNotSet()
                ->children()
                    ->arrayNode('clients')
                        ->prototype('array')
                            ->prototype('variable')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();
        // @formatter:on
    }

    public function loadComponent(ContainerBuilder $containerBuilder, ConfigurationInterface $configuration): void
    {
        $baseKey = Vairogs::VAIROGS . '.' . Component::AUTH . '.' . Component::AUTH_OPENID;
        $enabledKey = $baseKey . '.enabled';
        if ($containerBuilder->hasParameter($enabledKey) && true === $containerBuilder->getParameter($enabledKey)) {
            $clientsKey = $baseKey . '.clients';
            foreach ($containerBuilder->getParameter($clientsKey) as $key => $clientConfig) {
                $tree = new TreeBuilder($key);
                $node = $tree->getRootNode();
                $this->buildClientConfiguration($node);
                $config = (new Processor())->process($tree->buildTree(), [$clientConfig]);
                $clientServiceKey = $clientsKey . '.' . $key;

                foreach (Iter::makeOneDimension($config, $clientServiceKey) as $tkey => $value) {
                    $containerBuilder->setParameter($tkey, $value);
                }

                $this->configureClient($containerBuilder, $clientServiceKey, $clientsKey, $key);
            }
        }
    }

    /**
     * @noinspection NullPointerExceptionInspection
     */
    private function buildClientConfiguration(ArrayNodeDefinition $arrayNodeDefinition): void
    {
        $arrayNodeDefinition->addDefaultsIfNotSet();
        $optionsNode = $arrayNodeDefinition->children();

        // @formatter:off
        $optionsNode
            ->scalarNode('api_key')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('openid_url')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('preg_check')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('ns_mode')->defaultValue('sreg')->end()
            ->scalarNode('user_builder')->isRequired()->end()
            ->scalarNode('user_class')->defaultNull()->end()
            ->scalarNode('redirect_route')->isRequired()->cannotBeEmpty()->end()
            ->arrayNode('provider_options')->prototype('variable')->end()->end();
        // @formatter:on

        $optionsNode->end();
    }

    private function configureClient(ContainerBuilder $containerBuilder, string $clientServiceKey, string $base, string $key): void
    {
        $clientDefinition = $containerBuilder->register($clientServiceKey, OpenIDProvider::class);
        $clientDefinition->setArguments([
            new Reference('request_stack'),
            new Reference('router'),
            $key,
            $containerBuilder->getParameter('kernel.cache_dir'),
            $containerBuilder->getParameter($clientServiceKey),
        ])
            ->addTag($base);
    }
}
