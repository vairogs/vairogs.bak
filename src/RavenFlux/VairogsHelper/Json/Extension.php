<?php declare(strict_types = 1);

namespace RavenFlux\VairogsHelper\Json;

use Vairogs\Component\Utils\Helper\Json;
use Vairogs\Component\Utils\Twig\BaseExtension;

class Extension extends BaseExtension
{
    protected static string $suffix = '_json_';
    protected static string $class = Json::class;
}
