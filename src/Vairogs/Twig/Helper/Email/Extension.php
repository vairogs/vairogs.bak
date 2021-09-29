<?php declare(strict_types = 1);

namespace Vairogs\Twig\Helper\Email;

use Vairogs\Utils\Helper\Email;
use Vairogs\Utils\Twig\BaseExtension;

class Extension extends BaseExtension
{
    protected static string $class = Email::class;
}
