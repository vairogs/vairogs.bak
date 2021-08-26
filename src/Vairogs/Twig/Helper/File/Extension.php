<?php declare(strict_types = 1);

namespace Vairogs\Twig\Helper\File;

use Vairogs\Component\Utils\Helper\File;
use Vairogs\Component\Utils\Twig\BaseExtension;

class Extension extends BaseExtension
{
    protected static string $suffix = '_file_';
    protected static string $class = File::class;
}
