<?php declare(strict_types = 1);

namespace Vairogs\Twig\Helper\Email;

use Vairogs\Component\Utils\Helper\Email;
use Vairogs\Component\Utils\Twig\BaseExtension;

class Extension extends BaseExtension
{
    protected static string $suffix = '_email';
    protected static string $class = Email::class;
}
