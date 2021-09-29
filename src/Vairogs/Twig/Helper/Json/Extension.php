<?php declare(strict_types = 1);

namespace Vairogs\Twig\Helper\Json;

use Vairogs\Utils\Helper\Json;
use Vairogs\Utils\Twig\BaseExtension;

class Extension extends BaseExtension
{
    protected static string $class = Json::class;
}
