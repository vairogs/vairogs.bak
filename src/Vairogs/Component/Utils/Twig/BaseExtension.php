<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Twig;

use ReflectionException;
use Twig;
use Twig\Extension\AbstractExtension;
use Vairogs\Component\Utils\Annotation;
use Vairogs\Component\Utils\Vairogs;

abstract class BaseExtension extends AbstractExtension
{
    use TwigTrait;

    protected static string $suffix;
    protected static string $class;

    /**
     * @throws ReflectionException
     */
    public function getFilters(): array
    {
        return $this->makeArray(Helper::getFilterAnnotations(self::$class, Annotation\TwigFilter::class), Vairogs::VAIROGS . self::$suffix, Twig\TwigFilter::class);
    }

    /**
     * @throws ReflectionException
     */
    public function getFunctions(): array
    {
        return $this->makeArray(Helper::getFilterAnnotations(self::$class, Annotation\TwigFunction::class), Vairogs::VAIROGS . self::$suffix, Twig\TwigFunction::class);
    }
}
