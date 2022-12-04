<?php declare(strict_types = 1);

namespace Vairogs\Auth\DependencyInjection;

use Spaghetti\DependencyInjection\Dependency;
use Spaghetti\DependencyInjection\Traits\DependecyLoader;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Vairogs\Auth\OpenID\DependencyInjection\AuthOpenIDDependency;
use Vairogs\Auth\OpenIDConnect\DependencyInjection\AuthOpenIDConnectDependency;
use Vairogs\Core\Vairogs;
use Vairogs\Functions\Constants\Status;

use function sprintf;

class AuthDependency implements Dependency
{
    use DependecyLoader;

    public const AUTH = 'auth';

    public function getConfiguration(ArrayNodeDefinition $arrayNodeDefinition): void
    {
        $authNode = $arrayNodeDefinition
            ->children()
                ->arrayNode(name: self::AUTH)
                ->canBeEnabled();

        $this->appendComponent(class: AuthOpenIDDependency::class, arrayNodeDefinition: $authNode);
        $this->appendComponent(class: AuthOpenIDConnectDependency::class, arrayNodeDefinition: $authNode);

        $arrayNodeDefinition
            ->children()
            ->arrayNode(name: self::AUTH)
            ->children();

        $arrayNodeDefinition
            ->append(node: $authNode)
            ->end();
    }

    public function loadComponent(ContainerBuilder $container, ConfigurationInterface $configuration): void
    {
        $enabledKey = sprintf('%s.%s.%s', Vairogs::VAIROGS, self::AUTH, Status::ENABLED);

        if ($container->hasParameter(name: $enabledKey) && true === $container->getParameter(name: $enabledKey)) {
            $this->configureComponent(class: AuthOpenIDDependency::class, container: $container, configuration: $configuration);
            $this->configureComponent(class: AuthOpenIDConnectDependency::class, container: $container, configuration: $configuration);
        }
    }
}
