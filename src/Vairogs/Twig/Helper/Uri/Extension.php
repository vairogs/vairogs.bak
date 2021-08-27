<?php declare(strict_types = 1);

namespace Vairogs\Twig\Helper\Uri;

use Vairogs\Component\Utils\Helper\Uri;
use Vairogs\Component\Utils\Twig\BaseExtension;

class Extension extends BaseExtension
{
    protected static string $suffix = '_uri';
    protected static string $class = Uri::class;
}
