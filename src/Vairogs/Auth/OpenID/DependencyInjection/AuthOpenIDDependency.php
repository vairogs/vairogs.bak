<?php declare(strict_types = 1);

namespace Vairogs\Auth\OpenID\DependencyInjection;

use Spaghetti\DependencyInjection\Dependency;
use Spaghetti\DependencyInjection\Traits\ClientDependency;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\PropertyInfo\Type;
use Vairogs\Auth\DependencyInjection\AuthDependency;
use Vairogs\Auth\OpenID\OpenIDProvider;
use Vairogs\Core\Vairogs;
use Vairogs\Extra\Constants\Definition;
use Vairogs\Extra\Constants\Service;

class AuthOpenIDDependency implements Dependency
{
    use ClientDependency;

    public const AUTH_OPENID = 'openid';

    public function buildClientConfiguration(ArrayNodeDefinition $arrayNodeDefinition): void
    {
        $arrayNodeDefinition->addDefaultsIfNotSet();
        $optionsNode = $arrayNodeDefinition->children();

        /* @noinspection NullPointerExceptionInspection */
        $optionsNode
            ->arrayNode(name: 'provider_options')->prototype(type: Definition::VARIABLE)->end()->end()
            ->scalarNode(name: 'api_key')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode(name: 'ns_mode')->defaultValue(value: 'sreg')->end()
            ->scalarNode(name: 'openid_url')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode(name: 'preg_check')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode(name: 'redirect_route')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode(name: 'user_builder')->isRequired()->end()
            ->scalarNode(name: 'user_class')->defaultValue(value: null)->end();

        /* @noinspection PhpUnreachableStatementInspection */
        $optionsNode->end();
    }

    public function configureClient(ContainerBuilder $container, string $clientServiceKey, string $base, string $key): void
    {
        $clientDefinition = $container->register(id: $clientServiceKey, class: OpenIDProvider::class);
        $clientDefinition
            ->setArguments(arguments: [
                new Reference(id: Service::REQUEST_STACK),
                new Reference(id: Service::ROUTER),
                $key,
                $container->getParameter(name: Definition::KERNEL_CACHE_DIR),
                $container->getParameter(name: $clientServiceKey),
            ])
            ->addTag(name: $base);
    }

    /**
     * @noinspection NullPointerExceptionInspection
     */
    public function getConfiguration(ArrayNodeDefinition $arrayNodeDefinition): void
    {
        /* @noinspection PhpPossiblePolymorphicInvocationInspection */
        $arrayNodeDefinition
            ->children()
            ->arrayNode(name: self::AUTH_OPENID)
                ->canBeEnabled()
                ->addDefaultsIfNotSet()
                ->children()
                    ->arrayNode(name: 'clients')->prototype(type: Type::BUILTIN_TYPE_ARRAY)->prototype(type: Definition::VARIABLE)->end()->end()->end()
                ->end()
            ->end()
        ->end();
    }

    public function loadComponent(ContainerBuilder $container, ConfigurationInterface $configuration): void
    {
        $this->loadComponentConfiguration(base: Vairogs::VAIROGS . '.' . AuthDependency::AUTH . '.' . self::AUTH_OPENID, container: $container);
    }
}
