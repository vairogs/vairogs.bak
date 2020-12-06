<?php declare(strict_types = 1);

namespace RavenFlux\VairogsHelper;

use ReflectionException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Vairogs\Component\Utils\Annotation;
use Vairogs\Component\Utils\Helper\Gravatar;
use Vairogs\Component\Utils\Twig\Helper;
use Vairogs\Component\Utils\Twig\TwigTrait;
use Vairogs\Component\Utils\Vairogs;

class GravatarExtension extends AbstractExtension
{
    use TwigTrait;

    private const SUFFIX = '_gravatar_';

    /**
     * @return array
     * @throws ReflectionException
     */
    public function getFilters(): array
    {
        return $this->makeArray(Helper::getTwigAnnotations(Gravatar::class, Annotation\TwigFilter::class), Vairogs::RAVEN . self::SUFFIX, TwigFilter::class);
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    public function getFunctions(): array
    {
        return $this->makeArray(Helper::getTwigAnnotations(Gravatar::class, Annotation\TwigFunction::class), Vairogs::RAVEN . self::SUFFIX, TwigFilter::class);
    }
}