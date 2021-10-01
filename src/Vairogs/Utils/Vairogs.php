<?php declare(strict_types = 1);

namespace Vairogs\Utils;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Vairogs\Translatable\I18n\DependencyInjection\Compiler\RouterPass;

final class Vairogs extends Bundle
{
    public const VAIROGS = 'vairogs';

    public function build(ContainerBuilder $container): void
    {
        //$container->addCompilerPass(new RouterPass());
    }
}
