<?php declare(strict_types = 1);

namespace RavenFlux\VairogsHelper\Text;

use ReflectionException;
use Twig;
use Twig\Extension\AbstractExtension;
use Vairogs\Component\Utils\Annotation;
use Vairogs\Component\Utils\Helper\Text;
use Vairogs\Component\Utils\Twig\Helper;
use Vairogs\Component\Utils\Twig\TwigTrait;
use Vairogs\Component\Utils\Vairogs;

class Extension extends AbstractExtension
{
    use TwigTrait;

    private const SUFFIX = '_text_';

    /**
     * @throws ReflectionException
     */
    public function getFilters(): array
    {
        return $this->makeArray(Helper::getFilterAnnotations(Text::class, Annotation\TwigFilter::class), Vairogs::RAVEN . self::SUFFIX, Twig\TwigFilter::class);
    }

    /**
     * @throws ReflectionException
     */
    public function getFunctions(): array
    {
        return $this->makeArray(Helper::getFilterAnnotations(Text::class, Annotation\TwigFunction::class), Vairogs::RAVEN . self::SUFFIX, Twig\TwigFunction::class);
    }
}
