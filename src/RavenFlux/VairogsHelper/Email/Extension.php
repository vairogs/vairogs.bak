<?php declare(strict_types = 1);

namespace RavenFlux\VairogsHelper\Email;

use Vairogs\Component\Utils\Helper\Email;
use Vairogs\Component\Utils\Twig\BaseExtension;

class Extension extends BaseExtension
{
    protected static string $suffix = '_email_';
    protected static string $class = Email::class;
}
