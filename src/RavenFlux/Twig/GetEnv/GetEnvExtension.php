<?php declare(strict_types = 1);

namespace RavenFlux\Twig\GetEnv;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Vairogs\Component\Utils\Twig\TwigTrait;

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

        return $this->makeArray($input, 'raven', TwigFunction::class);
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
