<?php declare(strict_types = 1);

namespace RavenFlux\VairogsHelper\Text;

use Vairogs\Component\Utils\Helper\Text;
use Vairogs\Component\Utils\Twig\BaseExtension;

class Extension extends BaseExtension
{
    protected static string $suffix = '_text_';
    protected static string $class = Text::class;
}
