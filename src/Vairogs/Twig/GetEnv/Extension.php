<?php declare(strict_types = 1);

namespace Vairogs\Twig\GetEnv;

use JetBrains\PhpStorm\Pure;
use Vairogs\Component\Utils\Twig\Annotation;
use Vairogs\Component\Utils\Twig\BaseExtension;
use function getenv;

class Extension extends BaseExtension
{
    protected static string $class = self::class;

    #[Annotation\TwigFunction]
    #[Pure]
    public function getEnv(string $varname): mixed
    {
        if ($env = getenv($varname)) {
            return $env;
        }

        return $_ENV[$varname] ?? $varname;
    }
}
