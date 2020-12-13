<?php declare(strict_types = 1);

namespace RavenFlux\VairogsHelper\Uri;

use ReflectionException;
use Twig;
use Twig\Extension\AbstractExtension;
use Vairogs\Component\Utils\Annotation;
use Vairogs\Component\Utils\Helper\Uri;
use Vairogs\Component\Utils\Twig\Helper;
use Vairogs\Component\Utils\Twig\TwigTrait;
use Vairogs\Component\Utils\Vairogs;

class Extension extends AbstractExtension
{
    use TwigTrait;

    private const SUFFIX = '_uri_';

    /**
     * @return array
     * @throws ReflectionException
     */
    public function getFilters(): array
    {
        return $this->makeArray(Helper::getTwigAnnotations(Uri::class, Annotation\TwigFilter::class), Vairogs::RAVEN . '_text_', Twig\TwigFilter::class);
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    public function getFunctions(): array
    {
        return $this->makeArray(Helper::getTwigAnnotations(Uri::class, Annotation\TwigFunction::class), Vairogs::RAVEN . '_text_', Twig\TwigFunction::class);
    }
}
