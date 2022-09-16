<?php declare(strict_types = 1);

namespace Vairogs\Tests\Assets\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Vairogs\Twig\TwigTrait;

class TwigTestExtension extends AbstractExtension
{
    use TwigTrait;

    public function test(?string $value = null): string
    {
        return $value ?? __FUNCTION__;
    }

    public function getFunctions(): array
    {
        return $this->makeArray(input: ['test'], key: '', class: TwigFunction::class);
    }

    public function getFilters(): array
    {
        return $this->makeArray(input: ['test'], key: '', class: TwigFilter::class);
    }
}
