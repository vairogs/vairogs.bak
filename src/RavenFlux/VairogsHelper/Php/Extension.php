<?php declare(strict_types = 1);

namespace RavenFlux\VairogsHelper\Php;

use Vairogs\Component\Utils\Helper\Php;
use Vairogs\Component\Utils\Twig\BaseExtension;

class Extension extends BaseExtension
{
    protected static string $suffix = '_php_';
    protected static string $class = Php::class;
}
