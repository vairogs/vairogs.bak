<?php declare(strict_types = 1);

namespace Vairogs\Translatable\I18n\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Vairogs\Extra\Constants\Status;
use Vairogs\Translatable\I18n\DependencyInjection\TranslatableI18nDependency;

class RouterPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (true === $container->getParameter(sprintf('%s.%s', TranslatableI18nDependency::ALIAS, Status::ENABLED))) {
            $container->setAlias('router', sprintf('%s.router', TranslatableI18nDependency::ALIAS))->setPublic(true);
        }
    }
}
