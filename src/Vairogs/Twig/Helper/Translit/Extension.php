<?php declare(strict_types = 1);

namespace Vairogs\Twig\Helper\Translit;

use Vairogs\Utils\Helper\Translit;
use Vairogs\Utils\Twig\BaseExtension;

class Extension extends BaseExtension
{
    protected static string $class = Translit::class;
}
