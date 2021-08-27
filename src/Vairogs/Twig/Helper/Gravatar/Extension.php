<?php declare(strict_types = 1);

namespace Vairogs\Twig\Helper\Gravatar;

use Vairogs\Component\Utils\Helper\Gravatar;
use Vairogs\Component\Utils\Twig\BaseExtension;

class Extension extends BaseExtension
{
    protected static string $suffix = '_gravatar';
    protected static string $class = Gravatar::class;
}
