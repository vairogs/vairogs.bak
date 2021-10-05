<?php declare(strict_types = 1);

namespace Vairogs\Utils;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Vairogs\Translatable\I18n\DependencyInjection\Compiler\RouterPass;
use function class_exists;

final class Vairogs extends Bundle
{
    public const VAIROGS = 'vairogs';

    public function build(ContainerBuilder $container): void
    {
        if (class_exists(class: RouterPass::class)) {
            $container->addCompilerPass(pass: new RouterPass());
        }
    }
}
