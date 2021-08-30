<?php declare(strict_types = 1);

namespace Vairogs\Twig\Helper\Http;

use Vairogs\Component\Utils\Helper\Http;
use Vairogs\Component\Utils\Twig\BaseExtension;

class Extension extends BaseExtension
{
    protected static string $class = Http::class;
}
