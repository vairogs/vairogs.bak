<?php declare(strict_types = 1);

namespace Vairogs\Twig\Helper\Util;

use Vairogs\Component\Utils\Helper\Util;
use Vairogs\Component\Utils\Twig\BaseExtension;

class Extension extends BaseExtension
{
    protected static string $suffix = '_util_';
    protected static string $class = Util::class;
}
