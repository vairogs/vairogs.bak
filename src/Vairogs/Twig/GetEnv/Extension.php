<?php declare(strict_types = 1);

namespace Vairogs\Twig\GetEnv;

use JetBrains\PhpStorm\Pure;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Vairogs\Component\Utils\Twig\TwigTrait;
use Vairogs\Component\Utils\Vairogs;
use function getenv;

class Extension extends AbstractExtension
{
    use TwigTrait;

    public function getFunctions(): array
    {
        $input = [
            'getenv' => 'getEnv',
        ];

        return $this->makeArray($input, Vairogs::VAIROGS, TwigFunction::class);
    }

    #[Pure]
    public function getEnv(string $varname): mixed
    {
        if ($env = getenv($varname)) {
            return $env;
        }

        return $_ENV[$varname] ?? $varname;
    }
}
