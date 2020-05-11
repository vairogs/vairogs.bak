<?php declare(strict_types = 1);

namespace Vairogs\Component\Auth\OpenID\DependencyInjection;

use Exception;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Vairogs\Component\Auth\OpenID\OpenID;
use Vairogs\Component\Auth\OpenID\OpenIDProvider;
use Vairogs\Component\Utils\DependencyInjection\VairogsExtension;
use Vairogs\Component\Utils\Helper\Iter;
use Vairogs\Component\Utils\Vairogs;

class VairogsAuthOpenIDExtension extends VairogsExtension
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
        return OpenID::OPENID_BASE_ALIAS;
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
        $this->buildClients($container, $configuration);
    }

    private function buildClients(ContainerBuilder $container, ConfigurationInterface $configuration): void
    {
        $base = Vairogs::ALIAS . '.' . $this->getExtensionAlias() . '.' . OpenID::ALIAS . '.clients';
        foreach ($container->getParameter($base) as $key => $clientConfig) {
            $tree = new TreeBuilder($key);
            $node = $tree->getRootNode();
            $configuration->buildClientConfiguration($node);
            $config = (new Processor())->process($tree->buildTree(), [$clientConfig]);
            $clientServiceKey = $base . '.' . $key;

            foreach (Iter::makeOneDimension($config, $clientServiceKey) as $tkey => $value) {
                $container->setParameter($tkey, $value);
            }

            $this->configureClient($container, $clientServiceKey, $base, $key);
        }
    }

    public function configureClient(ContainerBuilder $container, $clientServiceKey, string $base, string $key): void
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
