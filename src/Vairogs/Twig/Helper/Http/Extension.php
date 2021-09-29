<?php declare(strict_types = 1);

namespace Vairogs\Twig\Helper\Http;

use Vairogs\Utils\Helper\Http;
use Vairogs\Utils\Twig\BaseExtension;

class Extension extends BaseExtension
{
    protected static string $class = Http::class;
}
