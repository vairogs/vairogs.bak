<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Twig;

use ReflectionException;
use Twig;
use Twig\Extension\AbstractExtension;
use Vairogs\Component\Utils\Twig\Annotation;
use Vairogs\Component\Utils\Vairogs;
use function get_class_vars;

abstract class BaseExtension extends AbstractExtension
{
    use TwigTrait;

    protected static string $suffix = '';
    protected static string $class;
    protected static string $key = Vairogs::VAIROGS;

    /**
     * @throws ReflectionException
     */
    public function getFilters(): array
    {
        $vars = get_class_vars(static::class);
        return $this->makeArray(Helper::getFilterAnnotations($vars['class'], Annotation\TwigFilter::class), $vars['key'] . $vars['suffix'], Twig\TwigFilter::class);
    }

    /**
     * @throws ReflectionException
     */
    public function getFunctions(): array
    {
        $vars = get_class_vars(static::class);
        return $this->makeArray(Helper::getFilterAnnotations($vars['class'], Annotation\TwigFunction::class), $vars['key'] . $vars['suffix'], Twig\TwigFunction::class);
    }
}
