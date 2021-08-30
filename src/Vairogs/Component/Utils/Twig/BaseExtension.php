<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Twig;

use ReflectionClass;
use ReflectionException;
use Twig;
use Twig\Extension\AbstractExtension;
use Vairogs\Component\Utils\Helper\Php;
use Vairogs\Component\Utils\Helper\Text;
use Vairogs\Component\Utils\Twig\Annotation;
use Vairogs\Component\Utils\Vairogs;
use function get_class_vars;
use function sprintf;
use function str_starts_with;
use function strtolower;

abstract class BaseExtension extends AbstractExtension
{
    use TwigTrait;

    protected static string $class;
    protected static string $key = '';
    protected static string $suffix = '';

    /**
     * @throws ReflectionException
     */
    public function getFilters(): array
    {
        $suffix = $this->getSuffix($vars = get_class_vars(static::class));
        return $this->makeArray(Helper::getFiltered($vars['class'], Annotation\TwigFilter::class), $suffix, Twig\TwigFilter::class);
    }

    /**
     * @throws ReflectionException
     */
    private function getSuffix(array $vars): string
    {
        $suffix = '' !== $vars['suffix'] ? $vars['suffix'] : $vars['class'];
        $ns = (new ReflectionClass($vars['class']))->getNamespaceName();
        $short = Php::getShortName($vars['class']);

        if ('Vairogs\Component\Utils\Helper' === $ns) {
            $suffix = sprintf('%s_%s', 'helper', $short);
        } elseif ('Extension' === $short) {
            $suffix = Text::getLastPart($ns, '\\');
        }

        if (str_starts_with($ns, 'Vairogs')) {
            $vars['key'] = Vairogs::VAIROGS;
        }

        if ('' !== $vars['key']) {
            $suffix = sprintf('%s_%s', $vars['key'], $suffix);
        }

        return strtolower($suffix);
    }

    /**
     * @throws ReflectionException
     */
    public function getFunctions(): array
    {
        $suffix = $this->getSuffix($vars = get_class_vars(static::class));
        return $this->makeArray(Helper::getFiltered($vars['class'], Annotation\TwigFunction::class), $suffix, Twig\TwigFunction::class);
    }
}
