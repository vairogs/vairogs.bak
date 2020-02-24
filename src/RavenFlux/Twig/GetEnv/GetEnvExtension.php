<?php declare(strict_types = 1);

namespace RavenFlux\Twig\GetEnv;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class GetEnvExtension extends AbstractExtension
{
    /**
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('raven_getenv', [
                $this,
                'getEnv',
            ]),
        ];
    }

    /**
     * @param string $varname
     *
     * @return mixed
     */
    public function getEnv(string $varname)
    {
        if ($env = getenv($varname)) {
            return $env;
        }

        return $_ENV[$varname] ?? $varname;
    }
}
