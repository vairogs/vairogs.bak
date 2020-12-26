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
     * @inheritDoc
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     */
    public function getConfiguration(ArrayNodeDefinition $node): void
    {
        // @formatter:off
        $node
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

    /**
     * @inheritDoc
     */
    public function loadComponent(ContainerBuilder $container, ConfigurationInterface $configuration): void
    {
        $enbledKey = Vairogs::VAIROGS . '.' . Component::AUTH . '.' . Component::AUTH_OPENID . '.enabled';
        if ($container->hasParameter($enbledKey) && true === $container->getParameter($enbledKey)) {
            $base = Vairogs::VAIROGS . '.' . Component::AUTH . '.' . Component::AUTH_OPENID . '.clients';
            foreach ($container->getParameter($base) as $key => $clientConfig) {
                $tree = new TreeBuilder($key);
                $node = $tree->getRootNode();
                $this->buildClientConfiguration($node);
                $config = (new Processor())->process($tree->buildTree(), [$clientConfig]);
                $clientServiceKey = $base . '.' . $key;

                foreach (Iter::makeOneDimension($config, $clientServiceKey) as $tkey => $value) {
                    $container->setParameter($tkey, $value);
                }

                $this->configureClient($container, $clientServiceKey, $base, $key);
            }
        }
    }

    /**
     * @param ArrayNodeDefinition $node
     * @noinspection NullPointerExceptionInspection
     */
    private function buildClientConfiguration(ArrayNodeDefinition $node): void
    {
        $node->addDefaultsIfNotSet();
        $optionsNode = $node->children();

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

    /**
     * @param ContainerBuilder $container
     * @param string $clientServiceKey
     * @param string $base
     * @param string $key
     */
    private function configureClient(ContainerBuilder $container, string $clientServiceKey, string $base, string $key): void
    {
        $clientDefinition = $container->register($clientServiceKey, OpenIDProvider::class);
        $clientDefinition->setArguments([
            new Reference('request_stack'),
            new Reference('router'),
            $key,
            $container->getParameter('kernel.cache_dir'),
            $container->getParameter($clientServiceKey),
        ])
            ->addTag($base);
    }
}
