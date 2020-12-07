<?php declare(strict_types = 1);

namespace RavenFlux\VairogsHelper\File;

use ReflectionException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Vairogs\Component\Utils\Annotation;
use Vairogs\Component\Utils\Helper\File;
use Vairogs\Component\Utils\Twig\Helper;
use Vairogs\Component\Utils\Twig\TwigTrait;
use Vairogs\Component\Utils\Vairogs;

class Extension extends AbstractExtension
{
    use TwigTrait;

    private const SUFFIX = '_file_';

    /**
     * @return array
     * @throws ReflectionException
     */
    public function getFilters(): array
    {
        return $this->makeArray(Helper::getTwigAnnotations(File::class, Annotation\TwigFilter::class), Vairogs::RAVEN . self::SUFFIX, TwigFilter::class);
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    public function getFunctions(): array
    {
        return $this->makeArray(Helper::getTwigAnnotations(File::class, Annotation\TwigFunction::class), Vairogs::RAVEN . self::SUFFIX, TwigFilter::class);
    }
}
