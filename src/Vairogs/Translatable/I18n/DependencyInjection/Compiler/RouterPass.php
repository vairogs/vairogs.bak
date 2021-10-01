<?php declare(strict_types = 1);

namespace Vairogs\Translatable\I18n\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RouterPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        //if (true === $container->getParameter(sprintf('%s.%s.%s', Vairogs::VAIROGS, Component::I18N, Status::ENABLED))) {
        //    $container->setAlias('router', sprintf('%s.%s.router', Vairogs::VAIROGS, Component::I18N))
        //        ->setPublic(true);
        //}
    }
}
