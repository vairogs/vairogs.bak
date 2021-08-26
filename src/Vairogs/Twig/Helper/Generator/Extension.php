<?php declare(strict_types = 1);

namespace Vairogs\Twig\Helper\Generator;

use Vairogs\Component\Utils\Helper\Generator;
use Vairogs\Component\Utils\Twig\BaseExtension;

class Extension extends BaseExtension
{
    protected static string $suffix = '_generator_';
    protected static string $class = Generator::class;
}
