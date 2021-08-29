<?php declare(strict_types = 1);

namespace Vairogs\Twig\Helper\Translit;

use Vairogs\Component\Utils\Helper\Translit;
use Vairogs\Component\Utils\Twig\BaseExtension;

class Extension extends BaseExtension
{
    protected static string $suffix = '_translit';
    protected static string $class = Translit::class;
}
