<?php declare(strict_types = 1);

namespace RavenFlux\VairogsHelper\Date;

use Vairogs\Component\Utils\Helper\Date;
use Vairogs\Component\Utils\Twig\BaseExtension;

class Extension extends BaseExtension
{
    protected static string $suffix = '_date_';
    protected static string $class = Date::class;
}
