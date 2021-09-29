<?php declare(strict_types = 1);

namespace Vairogs\Twig\Helper\Gravatar;

use Vairogs\Utils\Helper\Gravatar;
use Vairogs\Utils\Twig\BaseExtension;

class Extension extends BaseExtension
{
    protected static string $class = Gravatar::class;
}
