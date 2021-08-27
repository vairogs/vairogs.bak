<?php declare(strict_types = 1);

namespace Vairogs\Twig\Helper\Iter;

use Vairogs\Component\Utils\Helper\Iter;
use Vairogs\Component\Utils\Twig\BaseExtension;

class Extension extends BaseExtension
{
    protected static string $suffix = '_iter';
    protected static string $class = Iter::class;
}
