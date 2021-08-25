<?php declare(strict_types = 1);

namespace RavenFlux\VairogsHelper\Iter;

use Vairogs\Component\Utils\Helper\Iter;
use Vairogs\Component\Utils\Twig\BaseExtension;

class Extension extends BaseExtension
{
    protected static string $suffix = '_iter_';
    protected static string $class = Iter::class;
}
