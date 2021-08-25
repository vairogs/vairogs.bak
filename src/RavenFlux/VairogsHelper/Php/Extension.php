<?php declare(strict_types = 1);

namespace RavenFlux\VairogsHelper\Php;

use ReflectionException;
use Twig;
use Twig\Extension\AbstractExtension;
use Vairogs\Component\Utils\Annotation;
use Vairogs\Component\Utils\Helper\Php;
use Vairogs\Component\Utils\Twig\Helper;
use Vairogs\Component\Utils\Twig\TwigTrait;
use Vairogs\Component\Utils\Vairogs;

class Extension extends AbstractExtension
{
    use TwigTrait;

    private const SUFFIX = '_php_';

    /**
     * @throws ReflectionException
     */
    public function getFilters(): array
    {
        return $this->makeArray(Helper::getFilterAnnotations(Php::class, Annotation\TwigFilter::class), Vairogs::RAVEN . self::SUFFIX, Twig\TwigFilter::class);
    }

    /**
     * @throws ReflectionException
     */
    public function getFunctions(): array
    {
        return $this->makeArray(Helper::getFilterAnnotations(Php::class, Annotation\TwigFunction::class), Vairogs::RAVEN . self::SUFFIX, Twig\TwigFunction::class);
    }
}
