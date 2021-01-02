<?php declare(strict_types = 1);

namespace RavenFlux\GetEnv;

use JetBrains\PhpStorm\Pure;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Vairogs\Component\Utils\Twig\TwigTrait;
use Vairogs\Component\Utils\Vairogs;
use function getenv;

class GetEnvExtension extends AbstractExtension
{
    use TwigTrait;

    /**
     * @return array
     */
    public function getFunctions(): array
    {
        $input = [
            'getenv' => 'getEnv',
        ];

        return $this->makeArray($input, Vairogs::RAVEN, TwigFunction::class);
    }

    /**
     * @param string $varname
     *
     * @return mixed
     */
    #[Pure] public function getEnv(string $varname): mixed
    {
        if ($env = getenv($varname)) {
            return $env;
        }

        return $_ENV[$varname] ?? $varname;
    }
}
